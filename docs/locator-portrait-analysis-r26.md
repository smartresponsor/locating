Locator component – R26 portrait analysis (business and discovery coverage)

1. Purpose

This document gives a snapshot of how the Locator component looks as a
product, not only as a codebase. It focuses on business scenarios and
coverage along several axes:

- business logic
- business use case coverage
- observability and dashboards
- fixtures and test textures
- demo data
- discovery loop (how easy it is to explore and adopt the product)

2. Scenario matrix (short version)

Each scenario has a status:

- ok      – can be implemented with current Locator as-is, without gaps
- partial – core is there, but missing glue, docs or demos
- gap     – requires noticeable work before it can be offered as a product

Scenario S1 – checkout address validation (e-commerce)
- Status: ok / partial
- Notes:
  - AddressPipeline and AddressResult provide a solid base for validating
    and normalizing a shipping or billing address.
  - Suggest and reverse endpoints can support search-as-you-type and
    map-based selection.
  - Quota and metric infrastructure are present for multi-tenant setups.
  - Still missing a ready-made cookbook and sample request flows targeted
    specifically at checkout integrations.

Scenario S2 – customer address book (account profile)
- Status: partial
- Notes:
  - Core read-side functionality (validation, suggest, reverse) is fully
    usable for editing and confirming profile addresses.
  - There is no dedicated address book domain in Locator itself, so
    persistence and UI lists are outside the scope.
  - Needs a short guide that explains how to wire Locator into an existing
    account profile flow and how to treat invalid or incomplete addresses.

Scenario S3 – bulk address validation (import from CSV)
- Status: partial
- Notes:
  - Batch endpoint and pipeline can be used to validate many addresses in
    the background.
  - Tenant quotas and metrics cover throughput and simple SLOs.
  - Missing a reference script or tool to run a CSV file through Locator
    and collect a structured result (ok, partial, invalid).
  - No dedicated docs for long-running or scheduled bulk jobs yet.

Scenario S4 – store locator and pickup points (reverse geocoding)
- Status: ok / partial
- Notes:
  - Reverse geocoding is exposed via HTTP and backed by AddressReverse
    with provider routing.
  - This is enough to power a basic store locator front-end.
  - Still missing:
    - a concrete example of integration with a map widget,
    - guidance on caching and result reuse per tenant.

Scenario S5 – multi-tenant SaaS (per-tenant quotas and metrics)
- Status: ok
- Notes:
  - TenantQuotaManager and TenantGuard enforce per-tenant limits.
  - TenantUsageCounter and MetricRecorder store usage and counters.
  - R23 TenantQuotaManagerMetricDecorator gives visibility into allow and
    deny decisions.
  - SLO tools (fixtures, SLO runner) allow simple regression checks.
  - What is missing is mainly a short operator-facing doc for configuring
    tenants and quotas.

Scenario S6 – internal QA and regression testing
- Status: ok / partial
- Notes:
  - Golden fixtures (R22) and the fixture runner allow deterministic smoke.
  - Unit tests cover core service behavior and metric decorators.
  - SLO load runner (R25) gives a basic performance and reliability check.
  - Still useful:
    - more varied fixtures per country and per address pattern,
    - a single QA checklist that links tests, tools and metrics.

Scenario S7 – observability and dashboards
- Status: partial
- Notes:
  - Metrics exporter and metric recorder are in place.
  - There are SLO targets and a load runner that can feed numbers.
  - Missing ready-made Grafana dashboards and alert definitions.
  - Operators must still build their own views from raw metrics.

Scenario S8 – discovery loop for product and integration teams
- Status: partial
- Notes:
  - There are HTTP demo tools, curl examples, golden fixtures and a demo
    tenant concept.
  - Docker quickstart and CI docs show how to spin up Locator and attach
    smoke.
  - Still missing one guided "discovery path" that walks through:
    - bring Locator up on localhost,
    - run demos and fixtures,
    - inspect metrics,
    - try a realistic integration snippet.
  - Better story-telling and narrative docs would help non-engineers.

Scenario S9 – compliance, audit and error analysis
- Status: gap
- Notes:
  - Metrics and quotas give visibility into usage and errors.
  - There is no dedicated audit trail, log correlation guide or example
    of how to root-cause a problematic address or tenant.
  - No dedicated compliance section in docs (data retention, privacy).

Scenario S10 – productized "address quality" score
- Status: gap
- Notes:
  - AddressResult and validation issues are a good internal foundation.
  - There is no outward-facing "score" or rating that could be presented
    to business users.
  - Needs additional design (how to score, what is exposed, how to
    visualize in UI or reports).

3. Axis-based coverage snapshot

Axis A – business logic
- Rating: medium-high
- Rationale:
  - Core address pipeline, suggest and reverse, quotas and providers are
    implemented.
  - Enough to serve as a serious address API behind a web shop or CRM.

Axis B – business use case coverage
- Rating: medium
- Rationale:
  - Several key scenarios can already be assembled from existing pieces
    (checkout, profile, bulk).
  - However, lack of targeted guides and examples means more glue work
    for product teams.

Axis C – observability and dashboards
- Rating: low-medium
- Rationale:
  - Metrics and SLO tools exist, but dashboards and alerts are not yet
    provided as ready-to-import artifacts.

Axis D – fixtures and test textures
- Rating: low-medium
- Rationale:
  - Golden fixtures are present, but the data set is intentionally small
    and focused on a few countries.
  - There is room to extend to more patterns, edge cases and volumes.

Axis E – demo data and demo tenants
- Rating: low-medium
- Rationale:
  - There is a demo tenant concept and some demo scripts.
  - No single cohesive demo narrative that showcases Locator "end to end".

Axis F – discovery loop
- Rating: medium
- Rationale:
  - Good technical starting points (HTTP demos, Docker quickstart, SLO
    tools).
  - Still missing a non-technical view (PM-friendly path, screenshots,
    business examples).

4. Markers of full product readiness (target picture)

The following markers can be used as a checklist when driving Locator from
R-level to a true RC or GA:

M1 – one complete demo loop
- From clean checkout of Locator to:
  - running instance (docker or local PHP server),
  - demo tenant seeded,
  - a working end-to-end scenario: user types an address, sees suggestions,
    picks one, and the system saves a validated AddressResult,
  - metrics and SLO check can be observed on a dashboard.

M2 – business cookbook
- At least 5 to 10 short guides:
  - Locator in an e-commerce checkout,
  - Locator in a customer profile,
  - bulk import and validation,
  - store locator and pickup points,
  - multi-tenant SaaS story.
- Each guide references:
  - which endpoints to call,
  - what error patterns to expect,
  - how quotas and metrics behave.

M3 – observability pack
- Ready-to-import Grafana dashboard with:
  - request counts, error rates, p95 latency,
  - tenant-level and provider-level views.
- Alert rules for SLO breaches and provider failures.
- A short runbook: what to check when alerts fire.

M4 – QA and regression kit
- Extended fixture set with different address patterns and countries.
- A documented QA flow:
  - run unit tests,
  - run fixtures,
  - run SLO load for smoke,
  - review metrics and error logs.

M5 – minimal compliance and audit story
- A few pages of documentation that describe:
  - which address data is stored and for how long,
  - how to trace a problematic call,
  - how to export or delete tenant data if required.

5. Conclusion

R26 does not change code behavior. It gives a structured view on what is
already strong in Locator and where next R-iterations should focus in order
to reach full product readiness. The next logical steps are to convert some
of the "partial" and "gap" areas into concrete R-converts:
- demo / discovery loop,
- observability pack,
- business cookbook,
- compliance and audit story.
