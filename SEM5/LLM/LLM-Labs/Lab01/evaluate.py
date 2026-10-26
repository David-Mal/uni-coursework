from __future__ import annotations

import json
from pathlib import Path

from udllm_labkit import MockBackend, ModelBackend

from .classifier import classify_student_request


DATA_PATH = Path(__file__).with_name("data") / "requests.jsonl"


def evaluate(
    path: Path = DATA_PATH,
    *,
    backend: ModelBackend | None = None,
) -> dict[str, float]:
    rows = [json.loads(line) for line in path.read_text(encoding="utf-8").splitlines() if line]
    correct_intent = 0
    correct_review = 0
    total_latency = 0.0
    total_tokens = 0
    selected_backend = backend or MockBackend()
    for row in rows:
        result = classify_student_request(row["text"], backend=selected_backend)
        correct_intent += result.request.intent == row["intent"]
        correct_review += result.request.requires_human == row["requires_human"]
        total_latency += result.latency_ms
        total_tokens += result.input_tokens + result.output_tokens
    count = len(rows)
    return {
        "examples": float(count),
        "intent_accuracy": correct_intent / count,
        "review_accuracy": correct_review / count,
        "mean_latency_ms": total_latency / count,
        "mean_tokens": total_tokens / count,
    }


def main() -> None:
    print(json.dumps(evaluate(), indent=2, sort_keys=True))


if __name__ == "__main__":
    main()
