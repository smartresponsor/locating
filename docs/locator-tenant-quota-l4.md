# Locator L4 – Tenant config and quota guard

This envelope introduces per-tenant quota control for address operations while keeping the core Location pipeline untouched.

Key pieces:

- TenantContext / TenantContextInterface:
  - App\Entity\Locator\TenantContext
  - App\EntityInterface\Locator\TenantContextInterface
- TenantLimit / TenantLimitInterface:
  - App\Entity\Locator\TenantLimit
  - App\EntityInterface\Locator\TenantLimitInterface
- TenantConfigRepositoryInterface + ArrayTenantConfigRepository:
  - App\InfrastructureInterface\Locator\TenantConfigRepositoryInterface
  - App\Infrastructure\Locator\ArrayTenantConfigRepository
- TenantUsageCounterInterface + InMemoryTenantUsageCounter:
  - App\InfrastructureInterface\Locator\TenantUsageCounterInterface
  - App\Infrastructure\Locator\InMemoryTenantUsageCounter
- AddressQuotaGuardInterface + AddressQuotaGuard:
  - App\ServiceInterface\Locator\AddressQuotaGuardInterface
  - App\Service\Locator\AddressQuotaGuard
- AddressGeocodeBridge updated to call AddressQuotaGuard before external geocode:
  - If quota is exceeded, the result is returned without geocoding.
  - If quota is available, the router performs geocode and enriches the result.

Wiring example (pseudo):

- Wire TenantContext from security or tenant resolver.
- Provide tenant limits via ArrayTenantConfigRepository or a database-backed implementation.
- Use InMemoryTenantUsageCounter during development and replace it with Redis or database in production.

