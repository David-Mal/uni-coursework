from __future__ import annotations

import hashlib
import json
import re
import time
from dataclasses import dataclass
from typing import Any, Protocol, TypeVar

import httpx
from pydantic import BaseModel, ValidationError

from .config import ModelSettings


@dataclass(frozen=True)
class ModelReply:
    text: str
    input_tokens: int
    output_tokens: int
    latency_ms: float
    model: str


class ModelBackend(Protocol):
    def complete(
        self,
        messages: list[dict[str, str]],
        *,
        json_schema: dict[str, Any] | None = None,
    ) -> ModelReply: ...


def _estimated_tokens(text: str) -> int:
    return max(1, (len(text) + 3) // 4)


def _extract_after(text: str, marker: str) -> str:
    return text.split(marker, 1)[-1].strip() if marker in text else text.strip()


class MockBackend:
    """Deterministic test double used by the public tests.

    This class is deliberately small and rule based. It is not a language model and
    must not be used to estimate model quality.
    """

    model_name = "udllm-deterministic-test-double"

    def complete(
        self,
        messages: list[dict[str, str]],
        *,
        json_schema: dict[str, Any] | None = None,
    ) -> ModelReply:
        started = time.perf_counter()
        prompt = "\n".join(message["content"] for message in messages)
        if "<TASK>classify_student_request</TASK>" in prompt:
            text = self._classify(prompt)
        elif "<TASK>choose_course_tool</TASK>" in prompt:
            text = self._choose_tool(prompt)
        elif "<TASK>judge_answer</TASK>" in prompt:
            text = self._judge(prompt)
        else:
            text = json.dumps({"message": "Mock backend received the request."})
        latency_ms = (time.perf_counter() - started) * 1000
        return ModelReply(
            text=text,
            input_tokens=_estimated_tokens(prompt),
            output_tokens=_estimated_tokens(text),
            latency_ms=latency_ms,
            model=self.model_name,
        )

    @staticmethod
    def _classify(prompt: str) -> str:
        request = _extract_after(prompt, "STUDENT_REQUEST:").lower()
        if any(word in request for word in ("error", "install", "python", "run")):
            intent = "technical"
        elif any(
            word in request
            for word in (
                "grade",
                "absence",
                "absent",
                "certificate",
                "enrol",
                "enroll",
            )
        ):
            intent = "administrative"
        elif any(
            word in request
            for word in (
                "lecture",
                "lab",
                "deadline",
                "course",
                "exam",
                "project",
            )
        ):
            intent = "course_question"
        else:
            intent = "other"

        requires_human = any(
            phrase in request
            for phrase in ("grade appeal", "harassment", "speak to the teacher", "personal")
        )
        urgency = 5 if any(word in request for word in ("urgent", "today", "blocked")) else 1
        if "deadline" in request:
            urgency = max(urgency, 3)
        romanian_markers = ("ă", "â", "î", "ș", "ţ", "ț", "curs", "laborator")
        language = "ro" if any(marker in request for marker in romanian_markers) else "en"
        return json.dumps(
            {
                "intent": intent,
                "urgency": urgency,
                "language": language,
                "requires_human": requires_human,
            }
        )

    @staticmethod
    def _choose_tool(prompt: str) -> str:
        request = _extract_after(prompt, "STUDENT_REQUEST:").strip()
        lowered = request.lower()
        if any(word in lowered for word in ("submit", "send", "ask the teacher")):
            return json.dumps(
                {
                    "action": "submit_question",
                    "arguments": {
                        "subject": "Student question",
                        "message": request,
                        "idempotency_key": "mock-"
                        + hashlib.sha256(request.encode("utf-8")).hexdigest()[:20],
                    },
                    "message": "The question requires approval before submission.",
                    "needs_approval": True,
                }
            )
        if "deadline" in lowered:
            activity = "project" if "project" in lowered else "lab"
            return json.dumps(
                {
                    "action": "get_deadline",
                    "arguments": {"activity": activity},
                    "message": "Looking up the published deadline.",
                    "needs_approval": False,
                }
            )
        return json.dumps(
            {
                "action": "answer",
                "arguments": {},
                "message": "The request does not require a tool.",
                "needs_approval": False,
            }
        )

    @staticmethod
    def _judge(prompt: str) -> str:
        expected = _extract_after(prompt, "EXPECTED:").split("ACTUAL:", 1)[0]
        actual = _extract_after(prompt, "ACTUAL:")
        expected_tokens = set(re.findall(r"[a-z0-9]+", expected.lower()))
        actual_tokens = set(re.findall(r"[a-z0-9]+", actual.lower()))
        union = expected_tokens | actual_tokens
        score = len(expected_tokens & actual_tokens) / len(union) if union else 1.0
        return json.dumps(
            {
                "score": round(score, 3),
                "label": "pass" if score >= 0.5 else "fail",
                "reason": "Token-overlap test double; calibrate against human labels.",
            }
        )


class OpenAICompatibleBackend:
    """Minimal client for an OpenAI-compatible chat-completions endpoint."""

    def __init__(self, settings: ModelSettings):
        self.settings = settings

    def complete(
        self,
        messages: list[dict[str, str]],
        *,
        json_schema: dict[str, Any] | None = None,
    ) -> ModelReply:
        payload: dict[str, Any] = {
            "model": self.settings.model_name,
            "messages": messages,
            "temperature": 0,
        }
        if json_schema is not None:
            payload["response_format"] = {
                "type": "json_schema",
                "json_schema": {
                    "name": "structured_response",
                    "strict": True,
                    "schema": json_schema,
                },
            }

        started = time.perf_counter()
        with httpx.Client(timeout=self.settings.timeout_seconds) as client:
            response = client.post(
                f"{self.settings.base_url}/chat/completions",
                headers={"Authorization": f"Bearer {self.settings.api_key}"},
                json=payload,
            )
            response.raise_for_status()
            data = response.json()
        latency_ms = (time.perf_counter() - started) * 1000
        text = data["choices"][0]["message"]["content"]
        usage = data.get("usage", {})
        return ModelReply(
            text=text,
            input_tokens=int(usage.get("prompt_tokens", _estimated_tokens(str(messages)))),
            output_tokens=int(usage.get("completion_tokens", _estimated_tokens(text))),
            latency_ms=latency_ms,
            model=str(data.get("model", self.settings.model_name)),
        )


class FaultyBackend:
    """Fault injector used to test invalid model responses and timeouts."""

    def __init__(self, mode: str = "invalid_json"):
        self.mode = mode

    def complete(
        self,
        messages: list[dict[str, str]],
        *,
        json_schema: dict[str, Any] | None = None,
    ) -> ModelReply:
        if self.mode == "timeout":
            raise TimeoutError("Simulated model timeout")
        return ModelReply(
            text="this is not valid JSON",
            input_tokens=1,
            output_tokens=5,
            latency_ms=0.1,
            model="fault-injector",
        )


ModelT = TypeVar("ModelT", bound=BaseModel)


def _json_payload(text: str) -> str:
    stripped = text.strip()
    if stripped.startswith("```"):
        stripped = re.sub(r"^```(?:json)?\s*", "", stripped)
        stripped = re.sub(r"\s*```$", "", stripped)
    first = stripped.find("{")
    last = stripped.rfind("}")
    if first == -1 or last == -1 or first > last:
        raise ValueError("Model output does not contain a JSON object")
    return stripped[first : last + 1]


class StructuredModel:
    def __init__(self, backend: ModelBackend):
        self.backend = backend

    def generate(
        self,
        messages: list[dict[str, str]],
        response_model: type[ModelT],
    ) -> tuple[ModelT, ModelReply]:
        reply = self.backend.complete(
            messages,
            json_schema=response_model.model_json_schema(),
        )
        try:
            parsed = response_model.model_validate_json(_json_payload(reply.text))
        except (ValidationError, ValueError) as exc:
            raise ValueError(f"Invalid structured model response: {exc}") from exc
        return parsed, reply


def build_backend(settings: ModelSettings | None = None) -> ModelBackend:
    settings = settings or ModelSettings.from_environment()
    if settings.backend == "mock":
        return MockBackend()
    if settings.backend == "openai-compatible":
        return OpenAICompatibleBackend(settings)
    raise ValueError(
        "UDLLM_MODEL_BACKEND must be 'mock' or 'openai-compatible', "
        f"not {settings.backend!r}"
    )
