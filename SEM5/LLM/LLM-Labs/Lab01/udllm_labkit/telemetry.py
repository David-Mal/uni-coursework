from __future__ import annotations

import json
import time
import uuid
from contextlib import contextmanager
from dataclasses import asdict, dataclass
from pathlib import Path
from typing import Any, Iterator


@dataclass(frozen=True)
class Span:
    trace_id: str
    span_id: str
    name: str
    started_at: float
    duration_ms: float
    status: str
    attributes: dict[str, Any]


class TraceRecorder:
    """Small JSONL tracer used to make observability concepts inspectable."""

    def __init__(self, output_path: Path | None = None):
        self.trace_id = uuid.uuid4().hex
        self.output_path = output_path
        self.spans: list[Span] = []

    @contextmanager
    def span(self, name: str, **attributes: Any) -> Iterator[dict[str, Any]]:
        started_wall = time.time()
        started = time.perf_counter()
        mutable_attributes = dict(attributes)
        status = "ok"
        try:
            yield mutable_attributes
        except Exception as exc:
            status = "error"
            mutable_attributes["error.type"] = type(exc).__name__
            raise
        finally:
            span = Span(
                trace_id=self.trace_id,
                span_id=uuid.uuid4().hex,
                name=name,
                started_at=started_wall,
                duration_ms=(time.perf_counter() - started) * 1000,
                status=status,
                attributes=mutable_attributes,
            )
            self.spans.append(span)
            if self.output_path is not None:
                self.output_path.parent.mkdir(parents=True, exist_ok=True)
                with self.output_path.open("a", encoding="utf-8") as stream:
                    stream.write(json.dumps(asdict(span), sort_keys=True) + "\n")

