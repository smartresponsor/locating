# E2-M1 — Policy family retirement

This macro pack retires a bounded family of legacy policy service contracts from `Smartresponsor\ServiceInterface\Locator` and replaces them with App-owned bridge contracts under `App\Bridge\Legacy\Service\Location`.

Included family:
- BanditPolicy
- CircuitTuningPolicy
- CostAwarePolicy
- CostCapPolicy
- GeoFencePolicy
- IpAuthzPolicy
- Policy
- ProviderAuthzPolicy
- RegionPolicyEngine
- RegionTimeoutPolicy
- SlaPolicy

The runtime implementations remain in place, but their compatibility seam no longer depends on the legacy service-interface root.
