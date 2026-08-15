# Locating canonization audit and milestone — LC-01

## Scope

This document records the first canonization/control wave for the current Locating repository slice.
The goal is not a broad rewrite. The goal is to define the cleanup map, install a measurable gate,
and make only safe touched-file changes that prepare later namespace, class-form, Entity-first, and
root-structure waves.

## Current slice findings

### 1. Namespace state is transitional, not clean

`composer.json` currently exposes both production `App\` and compatibility `Smartresponsor\` PSR-4 roots
against the same `src/` tree. The source tree still contains a large amount of `Smartresponsor\...`
classes, especially in service, entity, infrastructure, integration, and test-adjacent structures.

Impact:

- Composer can resolve two conceptual ownership models from the same physical tree.
- Static analysis and refactors can hide drift because old and new namespaces coexist.
- Any future rename must be done in waves with reference scans, not with a blind search/replace.

Milestone target:

- Target production namespace: `App\...`.
- Temporary namespace bridge: `Smartresponsor\...` stays only until all live references are retired.
- Final bridge removal is a late milestone, not LC-01.

### 2. Class-form taxonomy is present but inconsistent

The repository already has strong Symfony-oriented layer names:

- `src/Entity`
- `src/EntityInterface`
- `src/Service`
- `src/ServiceInterface`
- `src/Service`
- `src/ServiceInterface`
- `src/Infrastructure`
- `src/InfrastructureInterface`
- `src/Message`
- `src/MessageHandler`

However, the class naming is not consistently self-descriptive. Several files under legacy bridges and
infrastructure carry generic names such as cache, logger, metrics, rate-limit, result, trace, and helper
forms without a stable Locating/Location-oriented prefix/suffix convention.

Milestone target:

- Every PHP class/interface name must expose both its business subject and its technical form.
- Acceptable examples:
  - `LocationAddressSuggestionEntity`
  - `LocationProviderHealthMetricEntity`
  - `LocationAddressSuggestService`
  - `LocationProviderHealthRecorderServiceInterface`
  - `LocationBatchJobMessage`
  - `LocationBatchJobMessageHandler`
- Generic interface names such as `CacheProbeInterface`, `LoggerLegacyInterface`, or `ResultCacheInterface`
  must be either retired, moved behind a better Symfony layer, or renamed in a controlled wave.

### 3. Entity-first shape is still partial

Locating already contains `src/Entity` and `src/EntityInterface`, but runtime and business concepts are still
spread across bridge, service, infrastructure, and model namespaces before the entity vocabulary is fully
canonized.

Milestone target:

1. Canonize the entity vocabulary first.
2. Normalize entity interfaces next.
3. Then align services, infrastructure, controllers, messages, tests, routes, and docs to that entity vocabulary.

This avoids a service-first cleanup where application code receives polished names while the persistent
business model remains ambiguous.

### 4. Root/deploy structure needs cleanup

The repository currently keeps nginx configuration under `config/nginx.conf`. In Symfony terms, `config/`
should remain application/runtime configuration, while deploy envelope files belong under `deploy/`.

LC-01 action:

- Add canonical `deploy/nginx/nginx.conf`.
- Retire `config/nginx.conf` through the apply script after backing it up.
- Document that Docker/nginx/compose/deploy files belong under `deploy/`, not `config/`.

### 5. Composer scripts currently reference a missing gate

`composer.json` references `.gate/check/location-protocol-canon.php`, but the current slice does not contain
that file. That makes `composer lint`, `composer canon`, and the full local pipeline brittle.

LC-01 action:

- Restore `.gate/check/location-protocol-canon.php` as a thin wrapper.
- Add `tools/canon/location-structure-audit.php` as the executable audit implementation.
- Add `composer canon:structure` and route `composer canon` through it.

## Milestone plan

### LC-01 — Control rail and deploy normalization

Deliverables:

- Audit/milestone document.
- Canonical nginx file under `deploy/nginx/nginx.conf`.
- Retire `config/nginx.conf` by touched-file deletion in the apply script.
- Executable structure audit/gate.
- Composer script repaired to use the new gate.

Validation:

```bash
composer validate
composer lint
composer canon:structure
```

### LC-02 — Entity-first inventory and naming map

Deliverables:

- Entity inventory: current file, class, namespace, proposed class name, proposed namespace, table-name expectation.
- EntityInterface mirror inventory.
- Collision map for `Location` vs `Locator` naming.
- Decision table for entity-scoped exceptions only.

No broad file moves yet. This wave produces the exact rename map.

### LC-03 — Entity and EntityInterface rename wave

Deliverables:

- Touched-file rename/move patch for entity classes and mirrored interfaces.
- Updated imports and tests for only those moved classes.
- Doctrine/table naming review if mappings exist.

Guardrail:

- Do not rename services/controllers before entity vocabulary is stable.

### LC-04 — Service and ServiceInterface class-form wave

Deliverables:

- Normalize `src/Service/*` and `src/ServiceInterface/*` to Location-prefixed business/form names.
- Preserve the existing Symfony-oriented layer shape.
- No `/src/Domain/`, no ports/adapters pattern.

### LC-05 — Infrastructure and Integration de-legacy wave

Deliverables:

- Convert generic infrastructure names into Location-prefixed infrastructure/service forms.
- Collapse or retire dead `Bridge/Legacy` survivors only after reference checks.
- Keep provider, cache, rate-limit, observability, and HTTP concerns type-readable.

### LC-06 — Service, route, OpenAPI, and public surface alignment

Deliverables:

- Align controller names, route names, OpenAPI tags, and docs with the final entity/service vocabulary.
- Keep HTTP contracts stable unless a route is explicitly deprecated.

### LC-07 — Test namespace and fixture alignment

Deliverables:

- Normalize `Tests\...` namespaces and folder mirrors.
- Move old `App\Tests\...` or `Smartresponsor\Tests\...` remnants into a consistent test namespace.
- Keep fixtures under `tests/fixtures` unless they become production demo fixtures.

### LC-08 — Final bridge shutdown readiness

Deliverables:

- Zero live `Smartresponsor\` references in production source/config/tests.
- Remove `Smartresponsor\` from composer autoload only after the gate reports it is safe.
- Remove stale legacy reports/docs only if explicitly selected as touched files.

## LC-01 acceptance criteria

- `deploy/nginx/nginx.conf` exists.
- `config/nginx.conf` is absent after apply, with backup created by the apply script.
- `.gate/check/location-protocol-canon.php` exists and is executable by PHP.
- `tools/canon/location-structure-audit.php` reports structure issues without crashing.
- `composer canon:structure` is available.
- No full repository overwrite or cleanup script is introduced.
