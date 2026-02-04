# Locator R18 – SLO, load and chaos kit

Goal: provide a minimal, reproducible way to validate Locator SLO targets and
prove that provider failover behaves correctly under stress.

## SLO targets (baseline)

These values can be tuned later per environment, but R18 uses:

- p95 latency for read operations (status/suggest/reverse) <= 700 ms
- error rate (HTTP/network) <= 0.5 %
- no hard failures when primary provider is down (failover kicks in)

## k6 scenario

File: `tools/locator-k6-slo-smoke.js`

Environment:

- `LOCATOR_BASE_URL` – base URL, e.g. `http://localhost:8000`

Run:

```bash
LOCATOR_BASE_URL=http://localhost:8000 k6 run tools/locator-k6-slo-smoke.js
```

What it does:

- `status_fast` – low-intensity checks for `/locator/status`
- `suggest_load` – ramping load for `/locator/address/suggest`
- `reverse_spike` – short spike against `/locator/address/reverse`

Thresholds:

- `http_req_failed: rate<0.005`
- `http_req_duration: p(95)<700`

If any threshold is violated, k6 exits with non-zero code which can be wired
into CI as a gate.

## PHP load runner

File: `tools/locator-load-runner.php`

Usage:

```bash
php tools/locator-load-runner.php  # or php tools/locator-load-runner.php 500
```

Environment:

- `LOCATOR_BASE_URL` – base URL, default `http://localhost:8000`
- `LOCATOR_REQUESTS` – total number of requests if CLI argument is not given

The script:

- mixes `/locator/status`, `/locator/address/suggest`, `/locator/address/reverse`
- measures latency and error rate
- prints p95 and error percentage
- exits with code 0 when SLO is met, or 2 when SLO is violated

## Chaos / failover tests

File: `tests/Locator/Service/AddressProviderRouterFailoverTest.php`

Scenarios:

- primary provider throws an exception, secondary returns a GeoPoint:
  router still succeeds and `providerKey()` points to `secondary`
- all providers fail:
  router returns `null` and `providerKey()` is `null`

This proves that at least the basic multi-provider failover is wired and
can be extended later with more advanced chaos engineering tools.
