Locator observability pack – R28

R28 delivers a small observability pack for the Locator component:

- metrics overview (how to read key metrics),
- a ready-to-import Grafana dashboard,
- example Prometheus alert rules.

1. Files

- `docs/locator-metrics-overview-r28.md`
  - explains the main metric families:
    - `locator_request_total`
    - `locator_request_latency_ms_bucket`
    - `tenant_quota_*`
  - provides example PromQL expressions for error rate and p95 latency.

- `tools/grafana/locator-dashboard-r28.json`
  - Grafana dashboard with:
    - top-level stats (RPS, error rate, p95 suggest/reverse),
    - graphs for request and error rate by operation,
    - latency heatmap,
    - tenant quota decisions panel.

- `docs/locator-alert-rules-r28.yaml`
  - Prometheus alert rules for:
    - high error rate (> 0.5 %),
    - high p95 latency for suggest/reverse (> 700 ms),
    - quota denial spikes for geocode.

2. Importing the dashboard

1. In Grafana, go to **Dashboards → New → Import**.
2. Paste the content of `tools/grafana/locator-dashboard-r28.json` or
   upload the file.
3. Select the Prometheus data source that scrapes your Locator
   `/metrics` endpoint.
4. Save the dashboard.

You should see:

- current requests per second,
- error percentages,
- p95 latency for suggest and reverse,
- latency distribution heatmap,
- quota decisions graph.

3. Loading the alert rules

1. Add `docs/locator-alert-rules-r28.yaml` to your Prometheus
   configuration (for example under `rule_files:`).
2. Reload Prometheus configuration.
3. Make sure that Alertmanager is configured and that routing for
   `service="locator"` alerts is set up.

The rules keep the thresholds aligned with the current SLOs:

- error rate <= 0.5 %,
- p95 latency <= 700 ms for suggest and reverse.

4. Relationship to SLO tools

The observability pack complements, but does not replace:

- SLO load runner (`tools/locator-slo-load.php`),
- golden fixture runner (`tools/locator-fixtures-run.php`).

Typical usage:

- run SLO and fixture tools in CI for early detection;
- rely on Prometheus + Grafana + alerts in staging and production to
  monitor behavior over time.

5. Next steps

Future R-iterations can expand the observability story by:

- adding per-tenant and per-provider panels,
- including provider health status and failover indicators,
- introducing duty runbooks that describe how to react to specific alerts.
