from udllm_labkit.retrieval import Chunk, TfidfIndex, extractive_answer


def test_retrieval_returns_relevant_chunk_and_citation() -> None:
    chunks = [
        Chunk("calendar.md", 0, "The project proposal deadline is Friday at noon."),
        Chunk("policy.md", 0, "Attendance is recorded during laboratory meetings."),
    ]
    index = TfidfIndex(chunks)
    results = index.search("When is the project proposal deadline?")
    answer, citations = extractive_answer("When is the project proposal deadline?", results)
    assert "Friday" in answer
    assert citations == ["[calendar.md#chunk-0]"]

