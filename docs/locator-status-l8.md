# Locator L8 – Status endpoint and metric snapshot

This envelope adds a small HTTP status endpoint on top of the observability hooks introduced in L7.

Interfaces and implementation:

- App\InfrastructureInterface\Locator\MetricSnapshotProviderInterface
  - snapshot(): returns a basic metric snapshot for Locator.

- App\Infrastructure\Locator\InMemoryMetricRecorder
  - Implements both MetricRecorderInterface and MetricSnapshotProviderInterface.
  - Aggregates latency sum, count and error count per operation.
  - Exposes:
    - count
    - errorCount
    - avgMs
    - errorRate

Status endpoint:

- App\ControllerInterface\Locator\StatusControllerInterface
- App\Controller\Locator\StatusController
- Route: GET /locator/status (config/routes/location_status.yaml)

Response shape:

{
  "service": "locator",
  "status": "ok" | "degraded",
  "metrics": {
    "address_pipeline": {
      "count": 100,
      "errorCount": 1,
      "avgMs": 12.3,
      "errorRate": 0.01
    },
    ...
  }
}

Basic SLO logic:

- If errorRate for any operation is above 0.5%, overall status changes to "degraded".
- Otherwise status is "ok".

InMemoryMetricRecorder is designed for development and smoke testing.
In production a dedicated MetricRecorderInterface implementation would usually export
to Prometheus or OpenTelemetry and the status endpoint can be wired to an external
metric backend instead of relying on local aggregates.
