from __future__ import annotations

import os
from dataclasses import dataclass


@dataclass(frozen=True)
class ModelSettings:
    backend: str = "mock"
    base_url: str = "http://localhost:1234/v1"
    model_name: str = "local-model"
    api_key: str = "local-development"
    timeout_seconds: float = 30.0

    @classmethod
    def from_environment(cls) -> "ModelSettings":
        return cls(
            backend=os.getenv("UDLLM_MODEL_BACKEND", "mock").strip().lower(),
            base_url=os.getenv(
                "UDLLM_MODEL_BASE_URL", "http://localhost:1234/v1"
            ).rstrip("/"),
            model_name=os.getenv("UDLLM_MODEL_NAME", "local-model"),
            api_key=os.getenv("UDLLM_MODEL_API_KEY", "local-development"),
            timeout_seconds=float(
                os.getenv("UDLLM_MODEL_TIMEOUT_SECONDS", "30")
            ),
        )

