# Locator CI SLO gate (R24)

This document describes a minimal CI pipeline with an SLO gate for the
Locator component.

The goal is to fail the pipeline when basic performance and reliability
objectives are not met, before promoting a build to RC or GA.

## SLO targets (pre-RC)

For the Locator HTTP API (status, suggest, reverse):

- p95 latency for read operations (suggest, reverse): <= 700 ms
- error rate: <= 0.5 % (HTTP 5xx or application-level errors)
- no regression in functional tests

These values are aligned with the broader Smartresponsor canon and can be
tightened later for GA.

## Suggested CI stages

1. Static checks and unit tests

   - `composer install --no-interaction --no-progress`
   - `composer test` (or `php ./vendor/bin/phpunit`)

2. Lightweight smoke / fixtures run

   - `php tools/locator-fixtures-run.php`
   - validates that a running instance responds correctly on a small golden
     dataset across multiple countries and reverse geocoding cases.

3. Load / SLO smoke

   - run a short scripted load (for example, with k6 or a PHP-based runner)
     that:
     - hits `/locator/address/suggest` and `/locator/address/reverse`
       with realistic queries and coordinates;
     - records p95 latency and error rate.

4. SLO evaluation

   - compute aggregated metrics from the load run;
   - compare against SLO targets;
   - fail the job if SLO is not met.

The provided GitHub Actions workflow `locator-ci-slo-gate.yml` shows a
baseline implementation, using the golden fixture runner as a simple smoke
step. A dedicated load tool (k6 or a PHP script) can be plugged into the
same pattern.

