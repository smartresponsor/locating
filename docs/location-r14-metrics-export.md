# Locator R14 – Metrics Export

Goal
----

Expose a Prometheus-style `/locator/metrics` endpoint that exports basic Locator metrics derived from the in-memory metric recorder, so that the service is observable out of the box.

Endpoint
--------

Path: `/locator/metrics`  
Method: `GET`  
Content-Type: `text/plain; version=0.0.4`

The endpoint exports the following series:

- `locator_request_total{operation="<name>"}` – total requests per operation.
- `locator_request_error_total{operation="<name>"}` – total failed requests per operation.
- `locator_request_latency_avg_ms{operation="<name>"}` – average latency in milliseconds.
- `locator_request_error_rate{operation="<name>"}` – error rate (0.0–1.0) per operation.

The data is based on the `MetricSnapshotProviderInterface::snapshot()` output.

Example response
----------------

```text
# HELP locator_request_total Total Locator requests per operation.
# TYPE locator_request_total counter
locator_request_total{operation="address_pipeline"} 42
# HELP locator_request_error_total Total Locator failed requests per operation.
# TYPE locator_request_error_total counter
locator_request_error_total{operation="address_pipeline"} 1
# HELP locator_request_latency_avg_ms Average Locator latency in milliseconds per operation.
# TYPE locator_request_latency_avg_ms gauge
locator_request_latency_avg_ms{operation="address_pipeline"} 35.000
# HELP locator_request_error_rate Locator error rate per operation.
# TYPE locator_request_error_rate gauge
locator_request_error_rate{operation="address_pipeline"} 0.02381
```

Prometheus scrape config
------------------------

Basic scrape configuration:

```yaml
scrape_configs:
  - job_name: 'locator'
    metrics_path: /locator/metrics
    static_configs:
      - targets: ['locator-service:8080']
```

SLO notes
---------

Using these metrics you can define SLOs such as:

- Read/pipeline:
  - `p95` latency derived from histograms or avg + additional buckets.
  - Error rate <= 0.5% for `address_pipeline`.
- Suggest:
  - `p95` latency similar constraints.
  - Error rate <= 1% for `address_suggest`.
