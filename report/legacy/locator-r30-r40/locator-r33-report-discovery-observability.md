Locator R33 – Discovery loop and observability template

Goal: define metrics, logs, and SLOs that describe Locator health.

Sections:

1. Core metrics
   - Requests per type (search, autocomplete, reverse)
   - Latency (p50, p95, p99) per operation
   - Error rate (by error class)
   - Cache hit ratio (if cache exists)

2. Logs
   - Structure for search events (fields: query, normalizedQuery, resultCount, topScore, etc.).
   - Structure for error events.
   - Sampling rules (if any).

3. SLO and alerts
   - SLO table: target latency, error rate, availability.
   - Alert rules: what triggers, what channel, who is on-call.

4. Discovery loop
   - How product/engineering reads metrics and decides improvements.
   - Cadence (weekly review, monthly deep dive).
