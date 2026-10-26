from __future__ import annotations

import math
import re
from collections import Counter
from dataclasses import dataclass
from pathlib import Path


TOKEN_PATTERN = re.compile(r"[a-zA-Z0-9]+")
STOPWORDS = {
    "a",
    "an",
    "and",
    "are",
    "at",
    "contains",
    "do",
    "does",
    "for",
    "how",
    "in",
    "is",
    "of",
    "on",
    "the",
    "to",
    "what",
    "when",
    "which",
}


def tokenize(text: str) -> list[str]:
    return [
        token.lower()
        for token in TOKEN_PATTERN.findall(text)
        if token.lower() not in STOPWORDS
    ]


@dataclass(frozen=True)
class Document:
    source: str
    text: str


@dataclass(frozen=True)
class Chunk:
    source: str
    chunk_id: int
    text: str

    @property
    def citation(self) -> str:
        return f"[{self.source}#chunk-{self.chunk_id}]"


@dataclass(frozen=True)
class SearchResult:
    chunk: Chunk
    score: float


def load_documents(directory: Path) -> list[Document]:
    documents: list[Document] = []
    paths = sorted(
        directory.iterdir(),
        key=lambda path: ("copy" in path.stem.lower(), path.name),
    )
    for path in paths:
        if path.suffix.lower() not in {".md", ".txt"}:
            continue
        documents.append(Document(source=path.name, text=path.read_text(encoding="utf-8")))
    return documents


def deduplicate_documents(documents: list[Document]) -> list[Document]:
    seen: set[str] = set()
    unique: list[Document] = []
    for document in documents:
        normalized = " ".join(document.text.split()).lower()
        if normalized in seen:
            continue
        seen.add(normalized)
        unique.append(document)
    return unique


def chunk_documents(
    documents: list[Document],
    *,
    chunk_size: int = 90,
    overlap: int = 15,
) -> list[Chunk]:
    if chunk_size <= 0:
        raise ValueError("chunk_size must be positive")
    if overlap < 0 or overlap >= chunk_size:
        raise ValueError("overlap must be non-negative and smaller than chunk_size")
    chunks: list[Chunk] = []
    step = chunk_size - overlap
    for document in documents:
        words = document.text.split()
        for chunk_id, start in enumerate(range(0, len(words), step)):
            part = words[start : start + chunk_size]
            if part:
                chunks.append(
                    Chunk(
                        source=document.source,
                        chunk_id=chunk_id,
                        text=" ".join(part),
                    )
                )
            if start + chunk_size >= len(words):
                break
    return chunks


class TfidfIndex:
    def __init__(self, chunks: list[Chunk]):
        if not chunks:
            raise ValueError("The index requires at least one chunk")
        self.chunks = chunks
        self.term_counts = [Counter(tokenize(chunk.text)) for chunk in chunks]
        document_frequency: Counter[str] = Counter()
        for terms in self.term_counts:
            document_frequency.update(terms.keys())
        count = len(chunks)
        self.idf = {
            term: math.log((1 + count) / (1 + frequency)) + 1
            for term, frequency in document_frequency.items()
        }
        self.vectors = [self._vector(terms) for terms in self.term_counts]

    def _vector(self, terms: Counter[str]) -> dict[str, float]:
        total = sum(terms.values()) or 1
        return {
            term: (frequency / total) * self.idf.get(term, 0.0)
            for term, frequency in terms.items()
        }

    @staticmethod
    def _cosine(left: dict[str, float], right: dict[str, float]) -> float:
        dot = sum(value * right.get(term, 0.0) for term, value in left.items())
        left_norm = math.sqrt(sum(value * value for value in left.values()))
        right_norm = math.sqrt(sum(value * value for value in right.values()))
        if left_norm == 0 or right_norm == 0:
            return 0.0
        return dot / (left_norm * right_norm)

    def search(self, query: str, *, k: int = 3) -> list[SearchResult]:
        query_vector = self._vector(Counter(tokenize(query)))
        scored = [
            SearchResult(chunk=chunk, score=self._cosine(query_vector, vector))
            for chunk, vector in zip(self.chunks, self.vectors, strict=True)
        ]
        return sorted(scored, key=lambda result: result.score, reverse=True)[:k]


def extractive_answer(
    question: str,
    results: list[SearchResult],
    *,
    minimum_score: float = 0.05,
) -> tuple[str, list[str]]:
    if not results or results[0].score < minimum_score:
        return "The supplied documents do not contain enough evidence to answer.", []

    question_terms = set(tokenize(question))
    candidates: list[tuple[int, str, Chunk]] = []
    for result in results:
        sentences = re.split(r"(?<=[.!?])\s+", result.chunk.text)
        for sentence in sentences:
            overlap = len(question_terms & set(tokenize(sentence)))
            candidates.append((overlap, sentence.strip(), result.chunk))
    _, sentence, chunk = max(candidates, key=lambda item: item[0])
    return f"{sentence} {chunk.citation}", [chunk.citation]
