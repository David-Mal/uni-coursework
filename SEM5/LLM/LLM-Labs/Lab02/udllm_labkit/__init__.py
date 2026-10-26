"""Small, inspectable utilities shared by the UDLLM laboratories."""

from .client import (
    FaultyBackend,
    MockBackend,
    ModelBackend,
    ModelReply,
    OpenAICompatibleBackend,
    StructuredModel,
    build_backend,
)
from .config import ModelSettings

__all__ = [
    "FaultyBackend",
    "MockBackend",
    "ModelBackend",
    "ModelReply",
    "ModelSettings",
    "OpenAICompatibleBackend",
    "StructuredModel",
    "build_backend",
]
