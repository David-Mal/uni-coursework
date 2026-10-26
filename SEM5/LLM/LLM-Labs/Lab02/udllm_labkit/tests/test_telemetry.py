from udllm_labkit.telemetry import TraceRecorder


def test_trace_records_status_and_attributes() -> None:
    recorder = TraceRecorder()
    with recorder.span("model.call", model="mock") as attributes:
        attributes["gen_ai.usage.input_tokens"] = 12
    assert recorder.spans[0].name == "model.call"
    assert recorder.spans[0].status == "ok"
    assert recorder.spans[0].attributes["gen_ai.usage.input_tokens"] == 12

