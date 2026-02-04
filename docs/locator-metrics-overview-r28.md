Locator metrics overview – R28

This document describes the core metrics exposed by the Locator component
via the Prometheus endpoint and how they relate to SLOs and dashboards.

1. Metric families

The following metric families are expected to be available in a typical
deployment (exact names may vary slightly per exporter implementation):

1.1 Request counters

- `locator_request_total{operation="pipeline|suggest|reverse|batch",status="ok|error"}`

  Meaning:
  - one counter per operation and status;
  - `operation` label indicates the logical operation;
  - `status` label is derived from application-level success/failure.

  Typical uses:
  - requests per second (RPS) per operation;
  - error rate (% of `status="error"` over total).

1.2 Latency histograms

- `locator_request_latency_ms_bucket{operation="pipeline|suggest|reverse|batch",le="..."}`
- `locator_request_latency_ms_sum`
- `locator_request_latency_ms_count`

  Meaning:
  - histogram of request latency in milliseconds;
  - buckets cover a small set of coarse ranges (for example: 50, 100,
    250, 500, 1000, 2000).

  Typical uses:
  - p95, p99 latency per operation;
  - comparison of latency distributions across tenants or providers.

1.3 Tenant quota counters

When the TenantQuotaManagerMetricDecorator is used, additional counters
are expected:

- `tenant_quota_geocode{result="ok|error"}` (example for `geocode` op)

  Meaning:
  - number of allow/deny decisions for the given operation;
  - `result="ok"` means the quota was not exceeded;
  - `result="error"` means the request was denied or soft-failed.

  Typical uses:
  - monitor when tenants hit their limits;
  - alert on unexpected spikes of quota denials.

2. SLO mapping

For the pre-RC SLOs defined in previous R-iterations:

- p95 latency for suggest/reverse: <= 700 ms
- error rate: <= 0.5 % (5xx and application-level errors)

Example Prometheus expressions:

- error rate for suggest:

  ```promql
  sum(increase(locator_request_total{operation="suggest",status="error"}[5m]))
    /
  sum(increase(locator_request_total{operation="suggest"}[5m]))
  ```

- p95 latency for reverse:

  ```promql
  histogram_quantile(
    0.95,
    sum by (le) (
      rate(locator_request_latency_ms_bucket{operation="reverse"}[5m])
    )
  )
  ```

3. Multi-tenant and provider views

If additional labels are added (for example `tenant` or `provider`), the
same expressions can be extended:

- error rate per tenant:

  ```promql
  sum by (tenant) (
    increase(locator_request_total{status="error"}[5m])
  )
    /
  sum by (tenant) (
    increase(locator_request_total[5m])
  )
  ```

- p95 latency per provider (assuming `provider` label is set):

  ```promql
  histogram_quantile(
    0.95,
    sum by (provider, le) (
      rate(locator_request_latency_ms_bucket[5m])
    )
  )
  ```

4. Relationship with tools

The following tools feed or rely on these metrics:

- `tools/locator-slo-load.php`
  - generates a short load against suggest/reverse endpoints and computes
    p95/error rate locally.
- `tools/locator-fixtures-run.php`
  - exercises golden fixtures and can be used to validate that metrics
    behave as expected for known requests.
- `/metrics` endpoint
  - serves Prometheus-formatted metrics; can be scraped by Prometheus and
    visualized in Grafana.

5. Next steps

R28 does not change the code that produces metrics. Instead it documents:

- which metric families are important;
- how to map them to SLOs;
- how to extend them with `tenant` and `provider` labels.

The next part of the observability pack is a ready-to-import Grafana
dashboard and example alert rules.
