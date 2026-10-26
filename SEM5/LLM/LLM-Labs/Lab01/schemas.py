from __future__ import annotations

from typing import Literal

from pydantic import BaseModel, ConfigDict, Field


class StudentRequest(BaseModel):
    model_config = ConfigDict(extra="forbid")

    intent: Literal["course_question", "administrative", "technical", "other"]
    urgency: int = Field(ge=1, le=5)
    language: Literal["en", "ro", "other"]
    requires_human: bool

