from __future__ import annotations

import tempfile
from pathlib import Path
from typing import Any, Literal

from pydantic import BaseModel, ConfigDict

from udllm_labkit import ModelBackend, StructuredModel, build_backend

from .tools import GetDeadlineArgs, QuestionStore, SubmitQuestionArgs, get_deadline


class ToolDecision(BaseModel):
    model_config = ConfigDict(extra="forbid")

    action: Literal["answer", "get_deadline", "submit_question"]
    arguments: dict[str, Any]
    message: str
    needs_approval: bool


class WorkflowResult(BaseModel):
    status: Literal["answered", "completed", "approval_required", "tool_error"]
    message: str
    tool_name: str | None = None
    tool_result: dict[str, str] | None = None


def plan(text: str, backend: ModelBackend | None = None) -> ToolDecision:
    model = StructuredModel(backend or build_backend())
    decision, _ = model.generate(
        [
            {
                "role": "system",
                "content": (
                    "Choose at most one course tool. Tool choice does not authorize "
                    "execution. Submission always requires user approval."
                ),
            },
            {
                "role": "user",
                "content": (
                    "<TASK>choose_course_tool</TASK>\n"
                    f"STUDENT_REQUEST: {text.strip()}"
                ),
            },
        ],
        ToolDecision,
    )
    return decision


def execute_request(
    text: str,
    *,
    store: QuestionStore,
    approved: bool = False,
    backend: ModelBackend | None = None,
) -> WorkflowResult:
    decision = plan(text, backend=backend)
    if decision.action == "answer":
        return WorkflowResult(status="answered", message=decision.message)

    if decision.action == "get_deadline":
        try:
            arguments = GetDeadlineArgs.model_validate(decision.arguments)
            result = get_deadline(arguments)
        except Exception as exc:
            return WorkflowResult(status="tool_error", message=str(exc), tool_name="get_deadline")
        return WorkflowResult(
            status="completed",
            message=result["message"],
            tool_name="get_deadline",
            tool_result=result,
        )

    if not approved:
        return WorkflowResult(
            status="approval_required",
            message="Review and approve the question before submission.",
            tool_name="submit_question",
        )
    try:
        arguments = SubmitQuestionArgs.model_validate(decision.arguments)
        result = store.submit(arguments)
    except Exception as exc:
        return WorkflowResult(status="tool_error", message=str(exc), tool_name="submit_question")
    return WorkflowResult(
        status="completed",
        message="The question was submitted.",
        tool_name="submit_question",
        tool_result=result,
    )


def main() -> None:
    with tempfile.TemporaryDirectory() as directory:
        store = QuestionStore(Path(directory) / "questions.json")
        preview = execute_request(
            "Submit this question: may I use a local model?",
            store=store,
        )
        print(preview.model_dump_json(indent=2))
        completed = execute_request(
            "Submit this question: may I use a local model?",
            store=store,
            approved=True,
        )
        print(completed.model_dump_json(indent=2))


if __name__ == "__main__":
    main()

