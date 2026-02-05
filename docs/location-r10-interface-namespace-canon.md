# Location R10 – Interface namespace canon (Smartresponsor\\*Interface\\Locator)

Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp

This envelope aligns all Locator interface namespaces with the Smartresponsor canon:

- `Smartresponsor\\EntityInterface\\Locator\\...`
- `Smartresponsor\\ServiceInterface\\Locator\\...`
- `Smartresponsor\\InfrastructureInterface\\Locator\\...`

## Scope

- Replace legacy `namespace Smartresponsor\\Domain\\Locator;` declarations
  in 78 `src/*Interface/Locator/*.php` files with layer-correct namespaces:
  - `src/EntityInterface/Locator/...` -> `namespace Smartresponsor\\EntityInterface\\Locator;`
  - `src/ServiceInterface/Locator/...` -> `namespace Smartresponsor\\ServiceInterface\\Locator;`
  - `src/InfrastructureInterface/Locator/...` -> `namespace Smartresponsor\\InfrastructureInterface\\Locator;`
- Keep all interface names and method signatures intact.
- No behaviour changes in implementations; only namespace alignment.

## Why this matters

- Makes autoloading and static analysis consistent with the folder structure.
- Removes the last traces of the experimental `Domain` layer from the Locator component.
- Ensures future implementations and decorators can type-hint interfaces
  using the predictable `Smartresponsor\\<Layer>Interface\\Locator` pattern.

## Affected families

Examples (non-exhaustive):

- Entity interfaces:
  - `HealthRecorderInterface`
  - `RecordReplayStoreInterface`

- Service interfaces:
  - `TenantQuotaInterface`, `TenantQuotaManagerInterface`, `TenantResourceQuotaInterface`
  - `RegionTimeoutPolicyInterface`, `RegionQuotaSchedulerInterface`
  - `GeoFencePolicyInterface`, `IpAuthzPolicyInterface`, `ProviderAuthzPolicyInterface`
  - `CircuitBreakerInterface`, `RateLimiterInterface`, `SlaPolicyInterface`
  - `TraceMiddlewareInterface`, `TracePropagatorInterface`
  - and other small policy/guard contracts created in L3–L8 envelopes.

- Infrastructure interfaces:
  - `ProviderAdapterInterface`
  - `PriorityBatchQueueInterface`
  - `CacheProbeInterface`, `CacheTtlPolicyInterface`, `CacheWarmerInterface`
  - `BudgetTelemetryInterface`, `StructuredLoggerInterface`

## Post-conditions

- No `namespace Smartresponsor\\Domain\\Locator;` remains in `src/`.
- Every interface under `src/*Interface/Locator` now has a namespace that
  matches its physical layer folder.
