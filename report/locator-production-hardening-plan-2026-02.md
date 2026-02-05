# Locator production hardening plan (2026-02)

## Scope and approach
- Performed a repository-level scan of runtime code, routing, contracts, tests, CI workflows, and operational docs.
- Goal: convert current state into a prioritized, implementation-ready engineering plan.

## Current-state findings

### 1) Architecture and boundaries
1. **Layering exists, but boundaries are blurred by parallel abstractions**.
   - The repository has many top-level slices (`Contract`, `ServiceInterface`, `Entity`, `Model`, `Integration`, `Infrastructure`, `Strategy`) that overlap in purpose. Example: two `LocatorInterface` contracts with different model dependencies:
     - `src/Contract/Locator/LocatorInterface.php`
     - `src/ServiceInterface/Locator/LocatorInterface.php`
2. **Namespace and model drift is visible**.
   - `src/ServiceInterface/Locator/LocatorInterface.php` imports `Smartresponsor\Domain\Locator\AddressData` and `GeoPoint`, but `src/Domain/Locator` contains only `.gitkeep`.
3. **Bundle / DI entry points are fragmented**.
   - Multiple extension/configuration artifacts exist:
     - `src/Bundle/DependencyInjection/Configuration.php`
     - `src/Bundle/Locator/DependencyInjection/Configuration.php`
     - `src/Bundle/Locator/DependencyInjection/SmartResponsorLocatorExtension.php`
   - This increases ambiguity in Symfony wiring and ownership.

### 2) Runtime wiring and delivery readiness
1. **Route controller FQCNs were inconsistent with project namespace in YAML routes**.
   - YAML route configs referenced `App\Controller\...` while controllers live under `Smartresponsor\Controller\...`.
   - Fixed in this pass:
     - `config/routes/locator_address_suggest.yaml`
     - `config/routes/locator_metrics.yaml`
     - `config/routes/locator_status.yaml`
2. **HTTP front controller is minimal health payload only**.
   - `public/index.php` always returns static JSON and does not boot Symfony kernel.
   - This is acceptable for smoke/demo mode but not production API hosting.
3. **Service registration is placeholder-only**.
   - `config/services.php` currently has no concrete service definitions.

### 3) Code quality and maintainability
1. **Formatting and style are inconsistent across files** (compressed one-line style vs PSR-like style).
   - Example contrast:
     - `src/Contract/Locator/LocatorInterface.php`
     - `src/Service/Locator/AddressProviderRouter.php`
2. **Copyright/header format is inconsistent**.
   - Mixed block comments and docblocks at file tops, including in interfaces and services.
3. **Potential dead/legacy overlap remains in source, not only docs**.
   - Presence of multiple representations (`Model/Locator/AddressData.php` and `Entity/Locator/AddressData.php`) suggests convergence debt.

### 4) Tests and determinism
1. **Test surface area is broad**, with unit/integration/contract/smoke suites configured in `phpunit.xml.dist`.
2. **Local execution bootstrap gap in fresh environment**.
   - `composer test` failed in this environment because `phpunit` was not available globally and dependencies were not installed.
   - CI mitigates this by invoking `php vendor/bin/phpunit ...` after `composer install`.
3. **No explicit coverage thresholds configured** in `phpunit.xml.dist`.

### 5) CI/CD and infrastructure
1. **CI is present and reasonably staged** (`lint` → `test`/`stan` → `smoke`) in `.github/workflows/ci.yml`.
2. **Composer scripts partially diverge from CI conventions**.
   - CI uses `php vendor/bin/phpunit ...`; `composer test` calls `phpunit` directly.
3. **No explicit release pipeline / artifact publishing flow** found for deployable package images.

### 6) Documentation and operations
1. **Repository contains extensive analysis docs** under `docs/` and `report/`, including architecture and RC-gate material.
2. **Top-level README is still “winner snapshot” oriented**, not operator/developer-production oriented (`README.md`).
3. **OpenAPI artifacts exist** (`docs/locator-openapi-v1.yaml`, `openapi/`) but there is no obvious contract-gate workflow tying API changes to versioning checks.

### 7) Data and migration posture
1. **Fixture strategy exists** (`fixtures/`, `tests/fixtures/`, multiple docs) and appears strong for deterministic demos/tests.
2. **No migration framework or schema evolution workflow observed** (likely because this repo is largely compute/integration centric).

## Prioritized task backlog

### P0 — Runtime correctness and contract integrity
1. **Unify route/controller namespace wiring across all route files**.
   - Include PHP route files (e.g., `config/routes/locator_reverse.php`) and verify container route loading.
2. **Create a route wiring test suite**.
   - Assert every configured controller FQCN exists and is invokable.
3. **Normalize primary contract package**.
   - Decide one canonical boundary (`Contract` or `ServiceInterface`) and deprecate the other via adapter shims.

### P1 — Architecture convergence
1. **Establish canonical domain model package**.
   - Consolidate duplicated value objects (e.g., `Model\Locator\AddressData` vs `Entity\Locator\AddressData`).
2. **Define dependency direction rules**.
   - Enforce with PHPStan/Psalm architecture rules (e.g., `Integration` cannot be imported by `Domain`/`Entity`).
3. **Rationalize bundle/extension entry points**.
   - One bundle, one extension, one configuration tree.

### P1 — Reliability and resilience hardening
1. **Standardize error taxonomy** across provider decorators, controllers, and bridges.
2. **Add idempotency and concurrency tests** for batch flows and caching/coalescing paths.
3. **Introduce explicit fallback behavior contracts** (provider timeout, quota exceeded, circuit open).

### P2 — Developer experience and docs
1. **Rewrite README for production onboarding**.
   - Include architecture map, local run modes, CI matrix, observability hooks, and release policy.
2. **Publish runbook pack**.
   - SLO/SLI definitions, alert interpretation, and incident response quick paths.
3. **Add API governance checklist**.
   - OpenAPI diff checks and compatibility policy in CI.

## Suggested commit units (implementation slices)

1. **Commit A — routing correctness**
   - Fix all route controller namespaces and add routing validation tests.
2. **Commit B — contract canonicalization phase 1**
   - Introduce canonical interface namespace and compatibility adapters.
3. **Commit C — model convergence phase 1**
   - Pick canonical `AddressData`/`GeoPoint`, migrate service signatures, add deprecation bridges.
4. **Commit D — CI consistency**
   - Align composer scripts with vendor-bin invocations; add architecture/static checks.
5. **Commit E — docs/runbooks refresh**
   - Replace snapshot README with operational guide and deployment checklist.

## Refactor blocks (larger, multi-commit)
1. **Boundary cleanup block**
   - Remove duplicate interface trees and align all services to one contract boundary.
2. **Data model cleanup block**
   - Converge value object classes and remove duplicate converters.
3. **Delivery pipeline block**
   - Add release tagging, package artifact generation, and contract-diff gate.

## Missing tests to add first
1. Route/controller FQCN integrity tests.
2. Contract compatibility tests between OpenAPI and controller DTO outputs.
3. Concurrency tests around cache/coalescing/rate-limit behavior.
4. Failure-injection tests for provider timeout/fallback/circuit-breaker combinations.

## Infrastructure pieces missing for production-grade operation
1. Release workflow with immutable build artifacts.
2. Contract-diff CI gate for OpenAPI changes.
3. Deployment manifests with environment contract (secrets, probes, autoscaling) if deployed as service.
4. Unified observability dashboard/runbook linkage from docs to metrics endpoints.
