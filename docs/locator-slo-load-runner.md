# Locator SLO load runner (R25)

R25 introduces a simple PHP-based SLO load runner that can be used in CI
or locally to verify that the Locator service meets basic SLO targets.

The runner:

- uses the golden fixture set `tests/Locator/Fixture/address-golden.ndjson`;
- performs multiple passes over `suggest` and `reverse` records;
- measures latency and error rate;
- computes p95 latency and error rate for the run;
- exits with code 0 when SLO targets are met, non-zero otherwise.

## File

- `tools/locator-slo-load.php`

## Environment

- `LOCATOR_BASE_URL` – base URL, default `http://localhost:8000`
- `LOCATOR_TENANT` – tenant id header, default `demo`
- `LOCATOR_SLO_ITERATIONS` – number of passes over the fixture set
  (default: `5`)
- `LOCATOR_SLO_P95_MS` – max allowed p95 latency in ms (default: `700`)
- `LOCATOR_SLO_ERROR_RATE` – max allowed error rate in percent
  (default: `0.5`)

## Usage

1. Start a Locator instance (for example with the Docker quickstart).

2. Run the load runner:

```bash
export LOCATOR_BASE_URL="http://localhost:8000"
export LOCATOR_TENANT="demo"

php tools/locator-slo-load.php
```

You will see per-request logs and a final summary:

- total number of requests;
- number of errors;
- error rate in percent;
- p95 latency in milliseconds;
- overall PASS/FAIL verdict.

## CI integration

In GitHub Actions, you can add a step to the existing
`locator-ci-slo-gate.yml` workflow:

```yaml
      - name: SLO gate (load runner)
        env:
          LOCATOR_BASE_URL: http://localhost:8000
          LOCATOR_TENANT: demo
        run: |
          php tools/locator-slo-load.php
```

Make sure that the Locator service is running and reachable from the job
environment (for example, via `docker-compose` or a dedicated service).
