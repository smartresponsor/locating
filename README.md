# Locator (Smartresponsor)

Locator is a PHP 8.2+ geocoding and address-processing component with provider routing, caching, resilience decorators, tenant-aware controls, and observability hooks.

## Quick start

1. Validate dependencies and metadata:
   ```bash
   composer validate --strict
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Run default test suite:
   ```bash
   composer test
   ```

## Project structure

- `src/Contract` and `src/ServiceInterface`: public and internal service contracts (currently being converged).
- `src/Service`, `src/Entity`: domain logic and value models.
- `src/Integration`, `src/Infrastructure`: provider adapters, caches, rate-limiters, telemetry, and runtime integration pieces.
- `src/Controller`: HTTP endpoints for locator operations and status/metrics.
- `config/routes`: route declarations for API endpoints.
- `tests/Locator`: unit, integration, contract, and smoke tests.
- `docs` and `report`: architecture notes, operational guidance, release-readiness and audit materials.

## Testing matrix

Defined in `phpunit.xml.dist`:
- `locator-unit`
- `locator-integration`
- `locator-contract`
- `locator-smoke`

Examples:
```bash
php vendor/bin/phpunit -c phpunit.xml.dist --testsuite locator-unit
php vendor/bin/phpunit -c phpunit.xml.dist --testsuite locator-contract
```

## CI

GitHub Actions workflow `.github/workflows/ci.yml` runs:
- Composer validation
- Syntax lint
- PHPUnit test suites
- PHPStan analysis
- Smoke gate checks

## Hardening reports

- Analysis: `report/locator-production-hardening-plan-2026-02.md`
- Concrete fix plan and implementation status: `report/locator-production-hardening-fixes-2026-02.md`
=======


Engineering hardening plan
- See `docs/locator-engineering-plan-2026-02.md` for a prioritized production-hardening backlog and commit units.