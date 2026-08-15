# E2-06 — Tenant legacy entity contract retirement

This wave retires the remaining Smartresponsor tenant context/limit/config contracts from the active quota guard legacy edge.

## Retired contracts
- `Smartresponsor\EntityInterface\Locator\TenantContextInterface`
- `Smartresponsor\EntityInterface\Locator\TenantLimitInterface`
- `Smartresponsor\InfrastructureInterface\Locator\TenantConfigRepositoryInterface`

## New App bridge contracts
- `App\Bridge\Legacy\Tenant\Location\TenantContextLegacyInterface`
- `App\Bridge\Legacy\Tenant\Location\TenantLimitLegacyInterface`
- `App\Bridge\Legacy\Tenant\Location\TenantConfigLegacyRepositoryInterface`

## Updated implementations
- `App\Infrastructure\Location\Tenant\RequestTenantContext`
- `Smartresponsor\Entity\Locator\TenantContext`
- `Smartresponsor\Entity\Locator\TenantLimit`
- `App\Infrastructure\Location\Tenant\ArrayTenantConfigRepository`
- `Smartresponsor\Service\Locator\AddressQuotaGuard`
