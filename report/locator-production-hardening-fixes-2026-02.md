# Locator production hardening fixes (2026-02)

## Implemented now

### 1) Architecture and boundaries
- Fixed broken interface dependencies in `src/ServiceInterface/Locator/LocatorInterface.php`:
  - removed references to non-existent `Smartresponsor\Domain\Locator\*`
  - switched to existing canonical value objects from `Smartresponsor\Entity\Locator\*`
- Normalized namespace mismatch in `src/ServiceInterface/Locator/LocatorServiceInterface.php` by moving from `Smartresponsor\ServiceInterface\Locator\Locator` to `Smartresponsor\ServiceInterface\Locator`.

### 2) Runtime routing correctness
- Fixed PHP route controller namespace drift in `config/routes/location_reverse.php` (`App\Controller\...` -> `Smartresponsor\Controller\...`).
- Added route-controller contract test `tests/Locator/Contract/RouteControllerWiringTest.php`:
  - parses YAML and PHP route files
  - enforces `Smartresponsor\Controller\...` namespace usage
  - checks controller class existence and `__invoke` method presence.

### 3) CI and local developer determinism
- Aligned Composer scripts with CI binary paths in `composer.json`:
  - `test` now uses `php vendor/bin/phpunit -c phpunit.xml.dist`
  - `test:locator` now uses vendor binary
  - `stan` now uses `php vendor/bin/phpstan ...`

### 4) Style consistency (targeted)
- Normalized updated PHP files to use one-line copyright header format:
  - line 2 after `<?php`, before `declare(strict_types=1);`
  - no multi-line C-style header blocks.

## Remaining work to implement all hardening points

### A. Architecture convergence
1. Consolidate duplicate abstractions (`Contract/*` vs `ServiceInterface/*`) behind one canonical API package.
2. Converge model duplication (`Model/Locator/*` and `Entity/Locator/*`) with staged deprecations.
3. Collapse duplicate bundle/configuration entry points into one extension tree.

### B. Reliability
1. Introduce unified exception taxonomy (provider timeout, upstream 4xx/5xx, quota, circuit-open).
2. Add deterministic chaos/failure-injection tests for fallback + circuit-breaker + retry combinations.
3. Add idempotency/concurrency tests for batch and cache-coalescing paths.

### C. Docs and operations
1. Replace snapshot-oriented README with production runbook-style README.
2. Add explicit deployment contract (env vars, health probes, secrets rotation).
3. Add OpenAPI compatibility gate and changelog policy to CI.

### D. Release and infrastructure
1. Add release workflow generating immutable versioned artifacts.
2. Add contract-diff checks and required status checks in gate workflow.
3. Add deployment manifests (or reference deployment module) for production environments.
