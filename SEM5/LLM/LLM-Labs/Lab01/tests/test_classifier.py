import pytest

from udllm_labkit import FaultyBackend, MockBackend

from lab01_model_contracts.classifier import classify_student_request
from lab01_model_contracts.evaluate import evaluate


def test_classification_is_typed_and_measured() -> None:
    result = classify_student_request(
        "The lab deadline is today.", backend=MockBackend()
    )
    assert result.request.intent == "course_question"
    assert result.request.urgency == 5
    assert result.input_tokens > 0


def test_empty_request_is_rejected() -> None:
    with pytest.raises(ValueError, match="must not be empty"):
        classify_student_request(" ", backend=MockBackend())


def test_malformed_output_is_rejected() -> None:
    with pytest.raises(ValueError, match="Invalid structured model response"):
        classify_student_request("A valid request", backend=FaultyBackend())


def test_reference_dataset_is_reproducible() -> None:
    metrics = evaluate()
    assert metrics["examples"] == 10
    assert metrics["intent_accuracy"] == 1.0
    assert metrics["review_accuracy"] == 1.0


def test_evaluation_uses_the_supplied_backend() -> None:
    with pytest.raises(ValueError, match="Invalid structured model response"):
        evaluate(backend=FaultyBackend())
