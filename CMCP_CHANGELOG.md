# CMCP execution journal

## 2026-09-20 — Locating RC hardening

### Reconnaissance baseline

- Read the Locating repository contract and primary package surfaces: `AGENTS.md`, `README.md`, `MANIFEST.json`, `composer.json`, `composer.prod.json`, PHPUnit/PHPStan configuration, CI workflow, bundle/extension surfaces, local canon scripts, Git status, and the repository inventory produced by the RC diagnostics.
- Read the applicable Canonization authority and rules: architecture authority model plus Canon001, Canon002, Canon007, Canon008, Canon009, Canon010, Canon018, Canon019, Canon020, Canon021, Canon022, Canon025, Canon026, Canon032, and Canon033.
- Read Gating's executable-contract documentation and inspected its available execution surfaces. The root PowerShell entrypoint cannot be invoked through Console MCP because repository PowerShell execution is restricted to `tool/` and `bin/`; allowed Composer/RC validation surfaces are used instead.
- Read the required Objecting, Cruding, Viewing, and Interfacing dependency contour as available locally, including their package/boundary contracts. Locating declares Objecting, Cruding, Viewing, and Interfacing as direct runtime dependencies and wires the local development copies through Composer path repositories with symlinks.
- Market/enterprise baseline reviewed for the Locating responsibility: provider quota/rate-limit handling, exponential backoff, cache-policy compliance, stable provider/place identity, observability, and regional/provider consistency remain relevant maturity expectations. These are kept separate from the RC-critical packaging/runtime work.

### Current repository state

- Branch: `env/locating-l2`; baseline HEAD: `e21466925125ca9de09e7901f3c312a28d39cb77`; upstream: `origin/env/locating-l2`; branch was 5 commits ahead and 0 behind at reconnaissance time.
- Pre-existing untracked `.gating/` is user/workspace-owned and is excluded from this run.
- Locating is a stateless location-processing component. Durable address persistence belongs outside this repository.
- Local `composer validate --strict` passes. Local `composer canon` passes with warning-only inventories, including 214 service-family warnings and 327 potentially non-canonical class/interface names.
- Canonization conflicts found: the repository still contains `src/Infrastructure*` topology prohibited by Canon019/020. That migration is too broad to combine unsafely with the first RC repair slice.
- The primary CI workflow uses PHP 8.2 even though Locating requires PHP 8.4. Development and production Composer manifests also permit Symfony 8.0 while Canon026 requires Symfony 8.1+.
- Locating exposes `LocatingBundle` but currently lacks the conventional standalone `bin/console` / `config/bundles.php` bootstrap expected by Canon025/032.

### RC-critical work selected

1. Align development and production Composer Symfony constraints to the canonical Symfony 8.1+ floor while preserving package identity parity.
2. Align GitHub Actions with the PHP 8.4 platform requirement so CI can actually resolve the declared package.
3. Add the minimal standalone Symfony bootstrap required for Locating's reusable bundle to be executable in standalone verification mode, without introducing Host coupling or generic CRUD ownership.
4. Add focused verification for the new bootstrap/package contract and run the repository's relevant quality gates.

### Material risks and exclusions

- Do not touch the pre-existing untracked `.gating/` directory.
- Do not move durable address persistence into Locating.
- Do not add generic CRUD controllers/routes; those remain owned by Cruding.
- Do not perform the large `Infrastructure*` topology migration as an unreviewed bulk rename. It remains a separate canonization workstream requiring semantic classification of each technical role and complete caller/config/test migration.
- Navigating is not modified because no navigation-item change is required.

### Gates

- `composer validate --strict`
- `composer run-script lint`
- `composer run-script cs:check`
- `composer run-script stan`
- `composer run-script test`
- `composer run-script canon`
- focused standalone bundle/container bootstrap verification
- final Git diff/status/branch/upstream inspection

### Growth workstream (post-RC)

- Provider-policy maturity: explicit retry/backoff/jitter budgets, quota/budget observability, provider cache-policy enforcement, and stable place/provider identity contracts.
- DX/API maturity: provider-neutral diagnostics, clearer failure taxonomy, contract examples, and controlled capability discovery.
- Canonization follow-up: eliminate the remaining `Infrastructure*` competing root topology by moving each class to its real Symfony technical role with mirrored interface paths and full caller/test/config migration.

### Implementation and verification results

- Raised the Symfony package floor to `^8.1` and aligned CI with PHP 8.4.
- Added the standalone Symfony runtime surface: `src/Kernel.php`, `config/bundles.php`, `bin/console`, and `runtime:verify`.
- Closed the first-party Composer dependency/repository graph for Collectioning and Tabling, added explicit local `dev-master` identity pins, and restored `composer.lock` as a versioned application artifact.
- Repaired Locating configuration loading, standalone provider/batch/observability/quota wiring, and the concrete DI cycles discovered by a real Symfony container compile.
- Added actionable redacted gitleaks diagnostics and a narrow allowlist for generated formatter cache and legacy SHA-256 inventory artifacts; the default gitleaks rules remain enabled.
- Materialized the missing repository Semgrep ruleset referenced by the existing security runner.
- `composer validate --strict`: passed.
- Syntax/lint gate: passed.
- `composer run-script cs:check`: passed.
- PHPStan at configured max level: passed with 0 errors.
- `composer run-script runtime:verify`: passed on PHP 8.4.13 / Symfony 8.1.7.
- Full PHPUnit suite: passed, 29 tests / 267 assertions.
- Symfony security suite: passed, 4 tests / 11 assertions.
- Composer vulnerability audit: passed, no vulnerability advisories.
- Importmap audit: not applicable because importmap is not configured.
- Gitleaks: passed after false-positive classification, 53 commits scanned, no leaks found.
- Canon and RC diagnostic validation: passed; RC diagnostic reports no blockers. Existing warning inventory remains explicit rather than being treated as errors.
- Semgrep: passed. The scanner runner now prefilters PHP files for the configured risky primitives, then invokes the strict Semgrep ruleset only on concrete candidates; the final gate scanned 1 candidate with 4 rules and reported 0 findings. The earlier Windows full-tree hangs were isolated to indiscriminate directory scanning and the redundant version preflight.

### Remaining RC tail

1. Stage only owned RC files; exclude pre-existing `.gating/` and `PRODUCT_CAPABILITY_AUDIT.adoc`.
2. Commit and push the current branch.
3. Verify final HEAD/upstream/worktree state and retain the documented Canon019/020 topology migration as a separate follow-up workstream.

### Canon019/020 migration slice: tenant quota contour

- Moved `ArrayTenantConfigRepository`, `InMemoryTenantUsageCounter`, and `RequestTenantContext` from `src/Infrastructure/Location/Tenant/` to `src/Service/Location/Tenant/`.
- Moved `TenantUsageCounterInterface` from `src/InfrastructureInterface/Location/Tenant/` to `src/ServiceInterface/Location/Tenant/`.
- Updated Symfony wiring, service callers, and all affected tests to the new canonical namespaces.
- Active old tenant Infrastructure FQCN references are gone; only regenerated/historical report data can mention the previous locations.
- Structural debt delta for this slice: `Infrastructure` files 64 -> 61; `InfrastructureInterface` files 52 -> 51.
- Verification: standalone runtime passed; PHPStan passed with 0 errors; full PHPUnit passed with 29 tests / 267 assertions; canon scripts passed with 0 errors.
- The service-family warning count moved from 214 to 222 because the migrated implementations now participate in the Service-family audit. This is tracked as naming/family cleanup, not a runtime or migration failure.

### Canon019/020 migration slice: configuration contour

- Moved `Env` from `src/Infrastructure/Location/Config/` to `src/Service/Location/Config/` and updated all active source/test callers.
- Moved `LocatorConfig` from `src/Infrastructure/Provider/Location/Config/` to `src/Service/Provider/Location/Config/`.
- Moved `LocatorConfigInterface` from `src/InfrastructureInterface/Provider/Location/Config/` to `src/ServiceInterface/Provider/Location/Config/`.
- Active old configuration Infrastructure FQCN references are gone.
- Structural debt delta after this slice: `Infrastructure` files 61 -> 59; `InfrastructureInterface` files 51 -> 50.
- Verification: standalone runtime passed; PHPStan passed with 0 errors; full PHPUnit passed with 29 tests / 267 assertions; canon scripts passed with 0 errors.
- Service-family warnings moved from 222 to 227 because the migrated implementations/interfaces now participate in the Service-family audit; tracked separately from structural-root removal.

### Canon019/020 migration slice: Batch contour

- Moved the complete `src/Infrastructure/Batch/Location/` implementation contour into canonical technical roles.
- Batch runtime/services now live under `src/Service/Batch/Location/`; Batch service contracts live under `src/ServiceInterface/Batch/Location/`.
- Reclassified `AddressBatchJobRecord` and `AddressBatchResultRecord` as data objects under `src/Model/Location/Batch/`, with their contracts under `src/ModelInterface/Location/` instead of retaining them as Service types.
- Updated all active source, Symfony config, message-handler, service, and test references. Old Batch Infrastructure FQCNs remain only in historical/generated reports.
- Structural debt delta after this slice: `Infrastructure` files 59 -> 44; `InfrastructureInterface` files 50 -> 37.
- Verification: standalone runtime passed; PHPStan passed with 0 errors; full PHPUnit passed with 29 tests / 267 assertions; canon scripts passed with 0 errors.
- Service-family warnings moved from 227 to 249 after the final Model reclassification (an intermediate all-Service placement reached 253); the remaining warnings are naming/family normalization debt, not structural-root failures.

### Canon019/020 migration slice: provider cache contour

- Moved `src/Infrastructure/Provider/Location/Cache/` to `src/Service/Provider/Location/Cache/` and the matching contracts to `src/ServiceInterface/Provider/Location/Cache/`.
- Preserved the separate `src/Integration/Provider/Location/Cache/` family because it has a different contract and constructor semantics; no cache implementations were merged implicitly.
- Updated all active source and test callers; old provider-cache Infrastructure FQCNs remain only in generated/historical reports.
- Exact structural-root delta for this slice: `Infrastructure` files 44 -> 39; `InfrastructureInterface` files 37 -> 32.
- Verification: standalone runtime passed; PHPStan passed with 0 errors; CS check passed; full PHPUnit passed with 29 tests / 267 assertions; canon scripts passed with 0 errors.
- Service-family warnings are 259 after the slice; these remain naming/family normalization debt rather than structural-root failures.

### Canon019/020 migration slice: live HTTP integration contour

- Moved the active generic `HttpClient` and `NominatimReverseHttpClient` implementations from `src/Infrastructure/Provider/Location/Http/` to `src/Integration/Provider/Location/Http/`.
- Moved the active HTTP contracts `HttpClientInterface`, `ReverseHttpClientInterface`, and `AddressReverseHttpBackendInterface` from `src/InfrastructureInterface/Provider/Location/Http/` to the existing canonical `src/Contract/Location/` root.
- Updated all active service, Symfony DI, gateway/backend, and test callers. The old live HTTP Infrastructure FQCNs have no active references.
- Left `CurlHttpClient`, `FixtureHttpClient`, their local interface, and the legacy HTTP `Kernel` in place for a separate compatibility/dead-code decision rather than relocating them blindly.
- Exact structural-root delta for this slice: `Infrastructure` files 39 -> 37; `InfrastructureInterface` files 32 -> 29.
- Verification: standalone runtime passed; PHPStan passed with 0 errors; CS check passed after import-order normalization; full PHPUnit passed with 29 tests / 267 assertions; canon scripts passed with 0 errors.

### Canon019/020 migration slice: metrics and recorder contour

- Created canonical `src/Recorder/` and `src/RecorderInterface/` role roots and moved `LocationMetricRecorder`, `InMemoryMetricRecorder`, `NullMetricRecorder`, `LocationMetricRecorderInterface`, and `InMemoryMetricRecorderInterface` into them.
- `InMemoryMetricRecorder` now explicitly implements its named `InMemoryMetricRecorderInterface` in addition to the shared metric recorder contract and snapshot provider contract.
- Moved non-recorder `BudgetTelemetry` and `HealthMetric` pairs into existing `Service/ServiceInterface` provider-location roles instead of keeping them under the Recorder root.
- Updated Symfony DI, metric backends, service decorators/providers, and tests to the canonical recorder contracts.
- Exact structural-root delta for this slice: `Infrastructure` files 37 -> 32; `InfrastructureInterface` files 29 -> 25.
- Verification: standalone runtime passed; PHPStan passed with 0 errors; CS check passed; full PHPUnit passed with 29 tests / 267 assertions; canon scripts passed with 0 errors (service-family warnings 263).

### Canon019/020 migration slice: observability deduplication

- Retired duplicate Infrastructure copies of `LogEvent` and `TracePropagator`; canonical Service implementations already existed and were functionally equivalent.
- Retired the duplicate Infrastructure `LogEventInterface`; canonical `ServiceInterface\\Provider\\Location\\Runtime\\Observability\\LogEventInterface` already exists and the Service `LogEvent` now implements it explicitly.
- Moved `TracePropagatorInterface` into the mirrored `ServiceInterface\\Observability\\Location` tree and rebound the canonical Service implementation to it.
- Verified that the four retired Infrastructure/InfrastructureInterface FQCNs have no active references.
- Exact structural-root delta for this slice: `Infrastructure` files 32 -> 30; `InfrastructureInterface` files 25 -> 23.
- Verification: standalone runtime passed; PHPStan passed with 0 errors; CS check passed; full PHPUnit passed with 29 tests / 267 assertions; canon scripts passed with 0 errors. Service-family warnings improved 263 -> 262 and potentially non-canonical names improved 328 -> 325.

### Canon019/020 migration slice: queue, resilience, and rate-limit contours

- Moved `Queue`, `Resilience`, and `RateLimit` implementation families from `src/Infrastructure/Provider/Location/` into mirrored `src/Service/Provider/Location/` technical-role paths.
- Moved their interfaces from `src/InfrastructureInterface/Provider/Location/` into matching `src/ServiceInterface/Provider/Location/` paths.
- Updated all active source/config/test references via exact namespace-prefix migration. Old FQCN matches remain only in generated reports and historical Console-MCP logs.
- Exact structural-root delta for this slice: `Infrastructure` files 30 -> 23; `InfrastructureInterface` files 23 -> 17.
- Verification: standalone runtime passed; PHPStan passed with 0 errors; CS check passed after import-order normalization; full PHPUnit passed with 29 tests / 267 assertions; canon scripts passed with 0 errors. Service-family warnings are 277 after making these service responsibilities visible to the family audit.

### Canon019/020 migration slice: backend, gateway, and store contours

- Moved 14 backend/gateway/store implementations from `src/Infrastructure/Provider/Location/` into the canonical `src/Service/Provider/Location/` role tree.
- Moved 12 paired interfaces from InfrastructureInterface Backend/Gateway/Store buckets into the mirrored `src/ServiceInterface/Provider/Location/` tree.
- Moved standalone `MetricSnapshotProviderInterface` to `src/Contract/Location/` because it is a recorder capability contract rather than a paired Service interface.
- Updated namespaces and 45 active source/config/test caller files through an exact symbol map; no broad Infrastructure prefix replacement was used.
- Exact structural-root delta for this slice: `Infrastructure` files 23 -> 9; `InfrastructureInterface` files 17 -> 4.
- Verification: standalone runtime passed; PHPStan passed with 0 errors; CS check passed after import-order normalization; full PHPUnit passed with 29 tests / 267 assertions; canon scripts passed with 0 errors. Service-family warnings are 296 after making these responsibilities visible to the family audit.

### Canon019/020 migration completion: final Infrastructure retirement

- Retired the remaining dead legacy Infrastructure-only surfaces: `TracingLocator`, the legacy HTTP client trio and HTTP `Kernel`, JSONL reader/writer helpers, the duplicate `ProviderCostCatalog`, and the orphan `LoggerInterface`.
- Moved active `ApiKeyAuth` into `Service/Provider/Location/Security` and updated its security fixture caller.
- Moved `AddressBatchJobRepositoryInterface` and `AddressBatchResultStorageInterface` into the mirrored `ServiceInterface/Batch/Location` tree and updated their backend consumers.
- Moved the active `ProviderCostCatalogInterface` into `ServiceInterface/Provider/Location`; the duplicate Infrastructure implementation was retired because `ProviderCostCatalogService` is behaviorally equivalent and is the configured runtime implementation.
- Updated LC-17 canon tooling so the retired legacy HTTP Kernel is forbidden and the canonical Symfony `src/Kernel.php` is required instead of checking obsolete manual service imports.
- Final structural-root result: `src/Infrastructure` declarations 9 -> 0 and `src/InfrastructureInterface` declarations 4 -> 0. Overall migration baseline: 64 -> 0 and 52 -> 0.
- Final verification at 0/0: standalone runtime passed; PHPStan passed with 0 errors; CS check passed; full PHPUnit passed with 29 tests / 267 assertions; canon and canon:all passed; RC validator returned `rc_diagnostic_green` with no blockers. The remaining structure-audit warning is naming-only (`316` potentially non-canonical names), and the RC canon scanner still reports one documentation TODO marker in `docs/location-r8-discovery-loop.md`.
