from __future__ import annotations

from dataclasses import dataclass

from udllm_labkit import ModelBackend, StructuredModel, build_backend

from .schemas import StudentRequest


SYSTEM_PROMPT = """Classify a student request for a university course.
Return only an object that satisfies the supplied schema.
Urgency uses a scale from 1 (routine) to 5 (immediate obstruction).
Set requires_human for appeals, safety concerns, personal cases, or an explicit
request to speak with a member of staff.
"""


@dataclass(frozen=True)
class Classification:
    request: StudentRequest
    model: str
    input_tokens: int
    output_tokens: int
    latency_ms: float


def classify_student_request(
    text: str,
    *,
    backend: ModelBackend | None = None,
) -> Classification:
    if not text.strip():
        raise ValueError("The student request must not be empty")
    structured_model = StructuredModel(backend or build_backend())
    request, reply = structured_model.generate(
        [
            {"role": "system", "content": SYSTEM_PROMPT},
            {
                "role": "user",
                "content": (
                    "<TASK>classify_student_request</TASK>\n"
                    f"STUDENT_REQUEST: {text.strip()}"
                ),
            },
        ],
        StudentRequest,
    )
    return Classification(
        request=request,
        model=reply.model,
        input_tokens=reply.input_tokens,
        output_tokens=reply.output_tokens,
        latency_ms=reply.latency_ms,
    )


def main() -> None:
    result = classify_student_request(
        "The project deadline is today and my environment will not run."
    )
    print(result.request.model_dump_json(indent=2))
    print(
        f"model={result.model} input_tokens={result.input_tokens} "
        f"output_tokens={result.output_tokens} latency_ms={result.latency_ms:.2f}"
    )


if __name__ == "__main__":
    main()

