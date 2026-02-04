# Location R9 – Status endpoint and in-memory metrics

Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp

This envelope focuses on making the Locator status surface production-friendly:
a lightweight `/status` endpoint backed by a simple in-memory metric recorder,
plus a PHPUnit guard for the JSON contract.

## Scope

- Provide a concrete implementation of `MetricSnapshotProviderInterface`
  for use by `StatusController`.
- Ensure metric aggregation is deterministic and suitable for smoke tests.
- Fix the `StatusControllerTest` so it validates the endpoint contract.

## Files

- `src/Infrastructure/Locator/InMemoryMetricRecorder.php`
  - Implements both `MetricRecorderInterface` and `MetricSnapshotProviderInterface`.
  - Aggregates per-operation:
    - total call count
    - error call count
    - average latency in milliseconds
    - error rate
  - Intended for development and tests; not a replacement for Prometheus.

- `tests/Locator/Status/StatusControllerTest.php`
  - Constructs `InMemoryMetricRecorder`, records a happy-path metric for `address_pipeline`.
  - Invokes `StatusController` with an empty `Request`.
  - Asserts:
    - HTTP 200 status code.
    - JSON body contains `service = locator` and `status = ok`.
    - `metrics` key exists and contains `address_pipeline` entry.

- `docs/location-r9-status-metrics.md`
  - This file – explains the intent and usage of the status endpoint and recorder.

## Behaviour

`InMemoryMetricRecorder::snapshot()` produces:

```php
[
    'address_pipeline' => [
        'count' => int,
        'errorCount' => int,
        'avgMs' => float,
        'errorRate' => float,
    ],
    // ...
]
```

`StatusController` uses this snapshot to derive an overall `status` field:

- `ok` – when no operation exceeds soft thresholds for latency/error.
- `degraded` – when any operation is over the threshold.
- `error` – reserved for future use if the recorder cannot provide data.

This keeps the status surface small and JSON-only, matching SmartResponsor canon
for lightweight service health endpoints.
