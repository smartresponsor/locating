# E2-M4 / Tenant + quota + region + health family retirement

This macro pack retires a bounded family of remaining legacy service-interface contracts around tenant, quota, region, health, timeout, toggle, token-bucket, and trace propagation helpers.

## Retired legacy interfaces
- HealthEwmaInterface
- QuotaGuardInterface
- RegionQuotaSchedulerInterface
- RegionRouterInterface
- TenantGuardInterface
- TenantQuotaInterface
- TenantResourceQuotaInterface
- TenantSeparatorInterface
- TimeoutCalibratorInterface
- ToggleInterface
- TokenBucketInterface
- TracePropagatorInterface

## New bridge seam
All compatibility contracts now live under `App\Bridge\Legacy\Service\Location\...`.
