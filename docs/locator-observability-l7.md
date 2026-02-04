# Locator L7 – Observability and SLO sketch

This envelope introduces a minimal but production-minded observability layer for Location/Locator.

Metric recorder:

- App\InfrastructureInterface\Locator\MetricRecorderInterface
  - recordLatency(operation, milliseconds)
  - incrementCounter(operation, result)

- App\Infrastructure\Locator\NullMetricRecorder
  - No-op implementation used when no metric backend is configured.
  - Can be replaced by an adapter for Prometheus, StatsD, OpenTelemetry, etc.

Decorators:

- App\Service\Locator\AddressPipelineMetricDecorator
  - Wraps AddressPipelineInterface.
  - Records:
    - latency in milliseconds under operation "address_pipeline"
    - counter "ok" or "error" for success/error.

- App\Service\Locator\AddressSuggestMetricDecorator
  - Wraps AddressSuggestInterface.
  - Records:
    - latency in milliseconds under operation "address_suggest"
    - counter "ok" or "error".

- App\Service\Locator\AddressBatchServiceMetricDecorator
  - Wraps AddressBatchServiceInterface.
  - Records:
    - latency of createJob under operation "address_batch_create"
    - "ok"/"error" counter for createJob.

Wiring suggestions (example):

- Register NullMetricRecorder as MetricRecorderInterface by default.
- In production, provide a MetricRecorderInterface implementation that forwards to:
  - Prometheus histogram and counter,
  - or OpenTelemetry meter,
  - or your existing metric backend.

- In service container:
  - Decorate AddressPipelineInterface → AddressPipelineMetricDecorator
  - Decorate AddressSuggestInterface → AddressSuggestMetricDecorator
  - Decorate AddressBatchServiceInterface → AddressBatchServiceMetricDecorator

SLO alignment (example targets):

- address_pipeline p95 ≤ 300 ms, error rate ≤ 0.5%
- address_suggest p95 ≤ 200 ms, error rate ≤ 0.5%
- address_batch_create p95 ≤ 1000 ms, error rate ≤ 0.5%

The actual SLO threshold and alert configuration is expected to live in the operations layer (Grafana/Alertmanager).
This envelope provides the code-level metric hooks in a canonical SmartResponsor layout.
