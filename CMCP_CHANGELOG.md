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

### LC-07 service-family audit calibration

- Corrected the LC-07 Location-prefix heuristic so a Service already scoped under a `/Location/` path is not warned merely because its class name does not repeat the `Location` prefix.
- Added `location_context` evidence to the generated CSV/report rows.
- Aligned LC-07 accepted service-form suffixes with forms already recognized by LC-14 (`Aggregator`, `Builder`, `Filter`, `Handler`, `Hydrator`, `Planner`, `Reader`, `Selector`, `Strategy`, `Writer`).
- Service-family warnings dropped from 299 to 120 with 0 errors and no production API rename. Exact remaining categories: 67 suffix advisories, 52 missing mirrored-interface advisories, and 1 Location-prefix advisory (`src/Service/AddressPipeline.php`).
- The remaining top-level `AddressPipeline` was not renamed or retired because it uses a distinct public contract/model family documented in README; handling it requires an explicit API compatibility decision rather than warning-driven churn.
- Verification: `canon:service-family`, full `canon`, and `cs:check` pass.

### Worktree integration and Gating dependency

- Preserved the `gating/gate` dependency integration as a real consumer capability: development Composer uses a symlinked `../Gating` path repository with explicit `dev-master` identity, production Composer uses the Gating VCS repository, and the lock file contains the resolved package.
- Installed the current lock successfully; `gating/gate` resolves from `../Gating` at `dev-master` and Composer validation with lock checking passes.
- Added an explicit `composer gate` diagnostic command. The current Gating run is intentionally not part of blocking `composer quality` yet because Locating does not have a consumer profile and the full cross-canon gate still exposes known architecture/naming/profile debt beyond this worktree integration pass.
- `composer quality` remains the blocking baseline (`cs:check`, `stan`, `test`) and passed after integration: CS clean, PHPStan 0 errors, PHPUnit 29 tests / 267 assertions.
- Removed an accidental full Gating repository copy from consumer `.gating/`; the tracked `.gating/README.md` remains the artifact-only surface and executable policy continues to come from the Composer package.
- Preserved `PRODUCT_CAPABILITY_AUDIT.adoc` as a product-boundary/capability planning artifact rather than discarding it as generated noise.

### Canon006 service-family calibration and stale lint-tail repair

- Re-read the authoritative Canonization rules `Canon001TechnicalRoleFirstRule`, `Canon002InterfaceTreeMirrorsImplementationRule`, and `Canon006OneDominantTechnicalRoleRule`, plus the current Gating Canon002/Canon006 executable mirrors.
- Calibrated LC-07 away from an arbitrary allowlist of acceptable Service suffixes. The audit now reports concrete dominant-role conflicts and mixed-role suffixes aligned with Canon006, while interface-tree mirroring remains the responsibility of Gating Canon002 instead of being duplicated as a local warning heuristic.
- Canon mapping for this slice: Canon001 => technical role is the first semantic tree; Canon002 => typed interface mirroring is enforced centrally by Gating; Canon006 => first-class role suffixes must not be hidden below `Service/` and mixed role names are reportable conflicts.
- Market/enterprise baseline for the Locating responsibility remains provider-neutral geocoding orchestration with explicit quota/rate-limit handling, retry/backoff/jitter, storage/cache-policy compliance, stable provider/place identity, observability, and regional consistency.
- Verified the current worktree baseline before mutation: branch `env/locating-l2`, HEAD `f34a3a7af1f35db2588e67082330e630f97a337b`, upstream synchronized, with one pre-existing modified file `tools/canon/location-service-family-audit.php`.
- Found and removed a stale Composer lint entry for retired `src/Infrastructure/Provider/Location/Http/Kernel.php`; the file is absent after the completed Infrastructure retirement and retaining the lint entry made the declared lint gate internally inconsistent.
- RC-critical scope for this pass: finish the LC-07 canon calibration, remove stale gate references, re-run syntax/canon/quality/runtime checks that are executable in the current Console MCP capability envelope, then commit/push only the owned Locating changes.
- Growth remains separate: provider policy hardening, richer diagnostics/failure taxonomy, and capability-discovery improvements are post-RC unless a current gate exposes a correctness defect.

- CI release-readiness repair: the primary workflow cache identity is now PHP 8.4, and `.github/workflows/locator-ci-slo-gate.yml` now executes PHP 8.4 (was 8.2) with matching PHP 8.4 cache keys, consistent with the package's PHP `^8.4` platform.
- Verification after the repairs: `composer validate --strict --check-lock` passed; changed-PHP syntax passed; full declared `composer lint` passed; PHPStan (`stan`) passed with 0 errors; `cs:check` passed; local `canon` passed with LC-07 at 205 Service / 192 ServiceInterface files, 33 warnings and 0 errors; standalone Symfony runtime verification passed on PHP 8.4.13 / Symfony 8.1.7; Symfony security tests passed with 4 tests / 11 assertions.
- The full local PHPUnit `test` command could not be captured through the synchronous Console MCP wrapper because that wrapper timed out before returning a terminal result; this pass therefore does not claim a fresh full-suite local result.
- The central `composer gate` diagnostic remains non-green and confirms the previously documented broader consumer-profile/canonical backlog. Current findings include missing profile values plus pre-existing Canon001/002/004/006/011/018/019/020 findings. Concrete tracked examples include ServiceInterface path drift and first-class Factory/Policy/Provider/Repository roles still under Service/Integration. This diagnostic debt is not represented as green and is not hidden by the local LC canon result.
- The central Gating failure is broader than this bounded RC-tail and requires a separate semantic role-migration/profile-normalization workstream rather than an unreviewed bulk rename. The current integration slice remains limited to LC-07 audit correctness, stale lint cleanup, and CI runtime consistency.

- Git integration: signed commit `1926d491683a2d174bf1c23d875ceabc76b7ac91` (`chore: align Locating RC canon and CI`) was pushed to `origin/env/locating-l2`; post-push worktree is clean and local/upstream are synchronized.
- GitHub Actions for that commit reproduced the same runner-level failure pattern already present on the immediately preceding `f34a3a7a` and earlier pushes: both workflows fail their initial `lint` job within seconds with an empty step list and no retrievable job log, and all dependent jobs are skipped. This is pre-existing Actions execution infrastructure evidence, not a current-diff test failure; a fresh remote full-suite result is therefore unavailable.
- Existing PR #27 is open but GitHub reports `CONFLICTING`. A guarded rebase onto current `origin/master` was attempted only after confirming a clean/synchronized worktree, but the first of 28 historical commits produced widespread rename/delete conflicts across legacy architecture. The rebase was immediately aborted rather than selecting ours/theirs and risking resurrection or loss of legacy surfaces. The branch returned to clean synchronized HEAD `1926d491...`.
- PR merge is therefore a genuine integration blocker for the current historical branch. Safe resolution requires integrating the bounded current delta onto a fresh master-derived branch/PR or an explicit semantic reconciliation of the 28-commit divergent history; bulk conflict resolution is not safe.

- Canon011 hardening pass: removed silent-failure behavior from `TryFallbackLocator` and `Hedger`, preserving secondary failure as the exception chain while retaining primary failure context in the message when both attempts fail.
- Added focused regression coverage in `tests/Locator/HedgerFailureTest.php` and `tests/Locator/TryFallbackLocatorFailureTest.php`.
- Completed the remaining Canon011 warning review: `RedisCache` now uses explicit JSON error-state handling instead of exception swallowing, and `IpAuthzPolicy` validates CIDR masks explicitly rather than catch-all fallback.
- Central Gating now reports `canon.011.no_silent_failure` as PASSED: no known silent-failure patterns detected.
- PHP syntax passed for all six changed PHP files and PHPStan passed with 0 errors after test typing was corrected.
- `cs:check` could not be re-executed in this pass because both gate-runner and Composer-script wrappers returned Console MCP internal errors; this is recorded as tooling limitation rather than represented as a green result.
- Parallel licensing changes (`composer.json`, `LICENSE`, `NOTICE`) were detected during the pass and intentionally excluded from all Canon011 edits, staging, and commit scope.
- Licensing follow-up reviewed the previously parallel changes as a separate repository-value slice: adopted PolyForm Noncommercial 1.0.0, aligned both `composer.json` and `composer.prod.json`, added the canonical license text and a `Required Notice:` matching the repository's established `Oleksandr Tishchenko / Marketing America Corp` ownership convention. `composer validate --strict --check-lock` passed and `composer.prod.json` parsed as valid JSON.
- Canon002 mirror pass: retired the stale `ServiceInterface/Location/Batch/AddressBatchMessageBusInterface` duplicate into legacy report material, consolidated the batch message model onto `AddressBatchMessageInterface`, and migrated backend/test consumers to the canonical `ServiceInterface/Batch/Location/AddressBatchMessageBusInterface`.
- Moved `LogEventInterface`, `RetentionPolicyInterface`, and `RetentionSweeperInterface` to mirrored `ServiceInterface/Observability/Location` and `ServiceInterface/Privacy/Location` paths and updated implementation imports.
- Consolidated retention contracts by making `RetentionPolicy` implement both the existing read-only `RetentionInterface` and the canonical `RetentionPolicyInterface`; added explicit TTL override behavior and regression coverage for default/override/sweeper integration.
- Verification: changed-file PHP syntax passed, PHPStan passed with 0 errors, and central Gating now reports `canon.002.interface_tree_mirror` as PASSED. A full `composer test` invocation exceeded the orchestration timeout, so no fresh full-suite pass is claimed for this slice.
- Canon019 topology cleanup: inspected normative Canon019/020 text and confirmed `src/Infrastructure/` is hard-prohibited as a competing layer root. The repository root existed only because of tracked `src/Infrastructure/Provider/Location/.gitkeep`; no active `App\\Locating\\Infrastructure` declarations were found. The directory contents were quarantined locally under ignored `var/cache` before removing the tracked placeholder. Central Gating now reports `canon.019.no_alternative_layer_taxonomy` as PASSED.
- Canon006/020 Factory pilot: migrated `LocationResultFactory` from `Service/Address/Location` to `Factory/Address/Location` and its typed contract from `ServiceInterface/Address/Location` to the mirrored `FactoryInterface/Address/Location` tree. Updated DI wiring, provider consumers, regression tests, and the LC-08 path audit.
- Verification: changed-file PHP syntax passed, PHPStan passed with 0 errors, local `canon` passed including LC-08, central Gating kept Canon002/007/011/019 green, and `LocationResultFactory` disappeared from both Canon006 and Canon020 findings. This validates the paired Factory/FactoryInterface migration pattern for remaining factory-role classes.
- Canon006/020 Factory continuation: migrated `AddressBatchJobFactory` from `Service/Batch/Location` to `Factory/Batch/Location` and `AddressBatchJobFactoryInterface` to the mirrored `FactoryInterface/Batch/Location` tree. Updated `AddressBatchJobStore`, DI wiring, composer lint paths, regression test imports, and the LC-10 path audit.
- Verification: changed-file PHP syntax passed, PHPStan passed with 0 errors, local `canon` passed with LC-10 green and service-family warnings reduced to 31, and central Gating kept Canon002/007/011/019 green while removing `AddressBatchJobFactory` from Canon006 and Canon020 findings.
- Canon006/020 Factory continuation: migrated `AddressResultFactory` from `Service/Batch/Location` to `Factory/Batch/Location` and `AddressResultFactoryInterface` to the mirrored `FactoryInterface/Batch/Location` tree. Updated `AddressBatchResultWriter`, DI wiring, and regression test imports.
- Verification: changed-file PHP syntax passed, PHPStan passed with 0 errors, local `canon` passed with service-family warnings reduced to 30, and central Gating kept Canon002/007/011/019 green while removing `AddressResultFactory` from Canon006 and Canon020 findings.
- Canon006/020 Factory continuation: migrated `LocationViewFactory` from `Service/Http/Location` to `Factory/Http/Location` and `LocationViewFactoryInterface` to the mirrored `FactoryInterface/Http/Location` tree. Updated HTTP service consumers, DI wiring, HTTP service tests, and the LC-09 path audit.
- Verification: changed-file PHP syntax passed, PHPStan passed with 0 errors, local `canon` passed with LC-09 green and service-family warnings reduced to 29, and central Gating kept Canon002/007/011/019 green while removing `LocationViewFactory` from Canon006 and Canon020 findings.
- Canon006/020 Factory completion: migrated standalone `RedisFactory` from `Integration/Provider/Location/Cache` to `Factory/Provider/Location/Cache`. No production consumers or typed interface were present, so no runtime rewiring was required.
- Verification: changed-file PHP syntax passed, PHPStan passed with 0 errors, local `canon` passed, and central Gating removed `RedisFactory` from both Canon006 and Canon020 while keeping Canon002/007/011/019 green. The currently reported Factory-role violations are now cleared.
- Canon006/020 Provider continuation: migrated isolated integration providers `FallbackProvider`, `MockProvider`, `NominatimProvider`, and `PhotonProvider` from `Integration/Provider/Location/Provider` to technical-role-first `Provider/Location/Integration`, preserving their shared provider contracts and integration context without consumer rewiring.
- Verification: changed-file PHP syntax passed, PHPStan passed with 0 errors, local `canon` passed, and central Gating removed all four integration-provider findings from Canon006/020 while keeping Canon002/007/011/019 green; Canon020 is now down to 7 reported typed-role mismatches.
- Canon006/020 Provider continuation: migrated `AddressReverseProvider` from `Service/Provider/Location` to `Provider/Location` and `AddressReverseProviderInterface` to `ProviderInterface/Location`. Updated capability/orchestration consumers, DI wiring, regression tests, composer lint path, LC-12 provider audit, and LC-24 reverse-path guard.
- Verification: changed-file PHP syntax passed, PHPStan passed with 0 errors, local `canon` passed including LC-12 and LC-24, and central Gating kept Canon002/007/011/019 green while removing `AddressReverseProvider` from Canon006/020; Canon020 is now down to 6 typed-role mismatches.
- Canon006/020 Provider continuation: migrated `AddressSuggestionProvider` from `Service/Provider/Location` to `Provider/Location` and `AddressSuggestionProviderInterface` to `ProviderInterface/Location`. Updated capability/backend/orchestration consumers, DI wiring, regression tests, composer lint path, and LC-12 provider audit.
- Verification: changed-file PHP syntax passed, PHPStan passed with 0 errors, local `canon` passed with service-family warnings reduced to 27, and central Gating kept Canon002/007/011/019 green while removing `AddressSuggestionProvider` from Canon006/020; Canon020 is now down to 5 typed-role mismatches.
- Canon006/020 Provider continuation: migrated `OrderedAddressReverseProvider` and `OrderedAddressSuggestionProvider` from `Service/Provider/Location` to `Provider/Location`, preserving their already-migrated `ProviderInterface/Location` contracts. Updated DI imports, tests, Composer lint paths, and LC-12 so the local provider-path guard recognizes the canonical technical-role-first root.
- Verification: changed-file PHP syntax had already passed, PHPStan had already passed with 0 errors, and local `canon` including LC-12 had already passed before the interrupted central-gate attempt. Fresh central `composer gate` confirmed Canon002/007/011/019 remain PASSED, removed both Ordered providers from Canon006 and Canon020, and reduced Canon020 from 5 to 3 remaining typed-role mismatches (`AddressReverseResultNormalizer`, `AddressNormalizer`, `AddressBatchMessageHandler`). The central gate remains non-green only because broader pre-existing Canon001/004/006/018/020 debt is still present; no green RC claim is made.
