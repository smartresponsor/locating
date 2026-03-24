# Location R40 — App provider signal infrastructure boundary

## Scope
This wave deepens the provider-signal seam introduced in R39.

## What changed
- Introduced App-owned provider signal infrastructure contracts:
  - `App\InfrastructureInterface\Provider\Location\ProviderHealthSnapshotStoreInterface`
  - `App\InfrastructureInterface\Provider\Location\ProviderQuotaDecisionGatewayInterface`
- Added legacy adapters behind those contracts:
  - `App\Infrastructure\Provider\Location\LegacyProviderHealthSnapshotStore`
  - `App\Infrastructure\Provider\Location\LegacyProviderQuotaDecisionGateway`
- Rewired signal readers so they no longer depend directly on legacy monitor/quota services.

## Effect
The policy layer still consumes App-owned signal readers and DTOs, but now those readers are also shielded from direct legacy dependencies.

Chain after R40:
- App policy
- App signal reader
- App provider-signal infrastructure contract
- legacy adapter
- legacy monitor/quota service
