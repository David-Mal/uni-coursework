from pydantic import BaseModel

from udllm_labkit.client import FaultyBackend, MockBackend, StructuredModel


class Answer(BaseModel):
    intent: str
    urgency: int
    language: str
    requires_human: bool


def test_structured_model_validates_mock_output() -> None:
    model = StructuredModel(MockBackend())
    answer, reply = model.generate(
        [
            {
                "role": "user",
                "content": (
                    "<TASK>classify_student_request</TASK>\n"
                    "STUDENT_REQUEST: The lab deadline is today and I am blocked"
                ),
            }
        ],
        Answer,
    )
    assert answer.intent == "course_question"
    assert answer.urgency == 5
    assert reply.input_tokens > 0


def test_invalid_json_is_not_silently_accepted() -> None:
    model = StructuredModel(FaultyBackend())
    try:
        model.generate([{"role": "user", "content": "test"}], Answer)
    except ValueError as exc:
        assert "Invalid structured model response" in str(exc)
    else:
        raise AssertionError("Expected malformed model output to be rejected")

