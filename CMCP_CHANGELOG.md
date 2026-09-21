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
