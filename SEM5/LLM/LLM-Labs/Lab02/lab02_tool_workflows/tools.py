from __future__ import annotations

import json
from pathlib import Path

from pydantic import BaseModel, ConfigDict, Field


class GetDeadlineArgs(BaseModel):
    model_config = ConfigDict(extra="forbid")
    activity: str = Field(min_length=2, max_length=40)


class SubmitQuestionArgs(BaseModel):
    model_config = ConfigDict(extra="forbid")
    subject: str = Field(min_length=3, max_length=80)
    message: str = Field(min_length=3, max_length=1000)
    idempotency_key: str = Field(min_length=6, max_length=120)


DEADLINES = {
    "lab": "Laboratory work is due before the next laboratory meeting.",
    "project": "The final project is demonstrated in week 14.",
}


def get_deadline(arguments: GetDeadlineArgs) -> dict[str, str]:
    activity = arguments.activity.lower()
    if activity not in DEADLINES:
        return {"status": "not_found", "message": "No published deadline was found."}
    return {"status": "ok", "message": DEADLINES[activity]}


class QuestionStore:
    def __init__(self, path: Path):
        self.path = path

    def _read(self) -> list[dict[str, str]]:
        if not self.path.exists():
            return []
        return json.loads(self.path.read_text(encoding="utf-8"))

    def submit(self, arguments: SubmitQuestionArgs) -> dict[str, str]:
        rows = self._read()
        existing = next(
            (
                row
                for row in rows
                if row["idempotency_key"] == arguments.idempotency_key
            ),
            None,
        )
        if existing is not None:
            return {"status": "duplicate", "question_id": existing["question_id"]}
        question_id = f"q-{len(rows) + 1:04d}"
        rows.append({"question_id": question_id, **arguments.model_dump()})
        self.path.parent.mkdir(parents=True, exist_ok=True)
        self.path.write_text(json.dumps(rows, indent=2), encoding="utf-8")
        return {"status": "created", "question_id": question_id}

