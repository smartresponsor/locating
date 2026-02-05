# Locator — production hardening plan (actionable)

## Scope and baseline reviewed
- Composition and runtime entry points: `composer.json`, `public/index.php`, `Makefile`.
- Layered source tree: `src/Controller`, `src/Service`, `src/Entity`, `src/Integration`, `src/Infrastructure`, plus `*Interface` families.
- Test and quality gates: `phpunit.xml.dist`, `.github/workflows/ci.yml`, `.github/workflows/gate.yml`.
- Contract and docs: `openapi/locator-v1.yaml`, `docs/locator-openapi-v1.yaml`, `README.md`, `docs/contributing.md`.

---

## A. Structured engineering analysis

### 1) Architecture and boundaries

#### Findings
1. **Layering is present but domain ownership is weak.**
   - The tree advertises many layers (`Domain`, `Service`, `Entity`, `Infrastructure`, `Integration`), but `src/Domain/Locator` is effectively empty (`.gitkeep` only) while business policies live mostly under `src/Service/Locator`.
2. **Class duplication across layers creates drift risk and unclear source-of-truth.**
   - Same business concept exists in multiple namespaces with near-identical implementation.
   - Examples:
     - `src/Service/Locator/RetryPolicy.php` and `src/Entity/Locator/RetryPolicy.php`
     - `src/Service/Locator/FailoverMatrix.php` and `src/Entity/Locator/FailoverMatrix.php`
     - `src/Service/Locator/TenantGuard.php` and `src/Entity/Locator/TenantGuard.php`
3. **Interface fragmentation is high.**
   - Overlapping interface families exist under `src/Contract/*`, `src/ServiceInterface/*`, `src/InfrastructureInterface/*`, and `src/EntityInterface/*`, making dependency direction and ownership harder to reason about.
4. **Controllers still carry orchestration/policy logic.**
   - `AddressSuggestController` performs request parsing, limit normalization, quota decision, and response mapping in one class.

#### Impact
- Harder refactors, potential inconsistent behavior between duplicated classes, and slower onboarding.

#### Strengthening direction
- Define one canonical namespace per concept (Domain or Service), convert others to adapters/deprecated shims, and enforce via architecture tests.

### 2) Code quality

#### Findings
1. **Repository contains mixed coding conventions and historical residue.**
   - README explicitly states this repository is a merged “winner snapshot,” which explains style and structure inconsistencies.
2. **Duplicate implementations indicate dead/parallel code paths.**
   - Retry policy duplicate is a concrete, low-risk candidate for unification.
3. **Documentation split and duplication.**
   - OpenAPI contract exists in two non-identical files: `openapi/locator-v1.yaml` and `docs/locator-openapi-v1.yaml`.

#### Impact
- Increased maintenance cost and risk of contract mismatch between runtime and docs.

### 3) Tests and quality signal

#### Findings
1. **Test catalog is broad and includes many focused suites under `tests/Locator`.**
2. **Some tests appear non-standard for PHPUnit 10 and may not produce strong signal.**
   - Example: `tests/Locator/RetryPolicyTest.php` uses native `assert()` and does not extend `PHPUnit\Framework\TestCase`.
3. **CI does run lint + unit + smoke + phpstan**, which is positive, but there is no coverage threshold enforcement in workflow.

#### Impact
- Potential false confidence: large test count but uneven assertion quality and uncertain execution strictness.

### 4) Reliability and predictability

#### Findings
1. **Randomized behavior without controllable seed/injected RNG in core policy logic.**
   - `delayMs()` in retry policy uses `random_int()`, which makes deterministic replay harder in higher-level flows.
2. **No lockfile (`composer.lock`) in repository.**
   - Builds can vary over time; reproducibility is reduced.
3. **Failover and resilience logic is spread over multiple classes and layers** (`Retry`, `CircuitBreaker`, `Hedger`, routers), increasing chance of policy divergence.

#### Impact
- Harder incident replay and subtle environment-to-environment behavior changes.

### 5) Documentation and operations

#### Findings
1. **Operational artifacts exist** (`tools/grafana/*`, SLO docs, gate scripts), but top-level README is minimal and not a full runbook.
2. **No single “production operation path”** (deploy, rollback, canary, incident) in one entry document.

#### Impact
- New engineers need to discover behavior across many docs/scripts manually.

### 6) Data and migrations

#### Findings
1. **No clear first-class migrations folder/pipeline in main repo path.**
2. **There are fixture tools and docs, but no explicit schema lifecycle visible from primary docs.**

#### Impact
- Risk of entity/data-model drift as code evolves.

### 7) CI/CD and infrastructure

#### Findings
1. **GitHub Actions exist and are reasonably structured** (`ci.yml`, `gate.yml`).
2. **Still missing industrial gates**: no mandatory coverage target, mutation testing, dependency audit, SBOM generation/signing.
3. **No `composer.lock` means dependency set is mutable in CI.**

#### Impact
- CI green does not yet guarantee production-grade repeatability and supply-chain traceability.

---

## B. Prioritized actionable backlog

### P0 — Stabilize correctness and predictability (next 1-2 sprints)
1. **Add and commit `composer.lock`.**
   - Owner: Platform.
   - Outcome: deterministic dependency graph.
2. **Unify duplicated `RetryPolicy` implementation into one canonical class.**
   - Keep backward compatibility via thin forwarding wrapper in deprecated namespace.
3. **Fix weak tests first (start with retry policy test family).**
   - Convert native `assert()` tests to `PHPUnit` assertions and strict test-case classes.
4. **Select one OpenAPI source-of-truth path and generate/verify the second copy from it.**

### P1 — Architecture hardening (1-2 months)
1. **Create `src/Domain/Locator` canonical policy model** (retry/failover/quota/tenant) and move invariants there.
2. **Define dependency direction rule set** (Controller -> Application Service -> Domain -> Integration).
3. **Reduce interface families to one ownership model** (`Contract` for public ports, internal interfaces near implementation).
4. **Extract controller request parsing into dedicated request DTO/mapper classes.**

### P2 — Industrial CI and operations (quarter)
1. **Add coverage job with threshold** for critical packages.
2. **Add mutation testing on resilience and routing components.**
3. **Add security and supply-chain checks** (audit, SBOM, license check).
4. **Publish one “production operations” doc** (deploy, rollback, incidents, SLO actions).

---

## C. Suggested commit units (concrete)

1. **Commit Unit CU-01: Dependency determinism**
   - Add `composer.lock`.
   - Update CI cache key to include lockfile hash.
2. **Commit Unit CU-02: RetryPolicy canonicalization**
   - Keep one implementation class.
   - Adapt imports/references.
   - Add compatibility shim + deprecation note.
3. **Commit Unit CU-03: Test correctness baseline**
   - Migrate weak tests (`tests/Locator/RetryPolicyTest.php` as first slice).
   - Add strict PHPUnit assertions.
4. **Commit Unit CU-04: OpenAPI source control**
   - Keep canonical OpenAPI in `openapi/`.
   - Add sync/check script for docs copy.
5. **Commit Unit CU-05: Docs/runbook uplift**
   - Expand README with quickstart, architecture map, operations links.

---

## D. Refactor blocks

### Refactor Block RB-1: Policy consolidation
- Targets: retry, failover, timeout, quota, tenant rules.
- Result: one cohesive domain policy package + explicit ports.

### Refactor Block RB-2: Namespace simplification
- Remove duplicate concept classes across `Entity`, `Service`, `Infrastructure` where business semantics are identical.

### Refactor Block RB-3: Controller slimming
- Move request normalization and response mapping into dedicated mappers/DTOs.

### Refactor Block RB-4: Contract and docs coherence
- Single OpenAPI source-of-truth with CI drift check.

---

## E. Missing tests (highest value)
1. Deterministic tests around retry delay strategy with injectable RNG.
2. Contract tests verifying both OpenAPI files are equivalent or generated from one source.
3. End-to-end tests for quota + failover interaction.
4. Regression tests for namespace compatibility shims during class consolidation.

---

## F. Missing infrastructure pieces for production readiness
1. Locked dependency graph (`composer.lock`) and reproducible artifact process.
2. Coverage + mutation + security/SBOM gates in CI.
3. Centralized operations runbook with rollback/checklist.
4. Explicit migration lifecycle and schema drift checks.

---

## G. Growth points vs strengthening points

### Growth points
- Rich feature surface in routing, quotas, failover, and observability-related modules.
- Existing CI skeleton and broad test directory are strong acceleration assets.

### Strengthening points
- Canonical architecture boundaries and code ownership.
- Determinism and dependency reproducibility.
- Contract/documentation single source of truth.
