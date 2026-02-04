# Location R11 – Provider failover and SLA-aware routing

Scope:
- Canonical ProviderAdapterInterface under App\\InfrastructureInterface\\Locator.
- Fixed FakeProvider and ProviderSandbox to use the canonical interface.
- Introduced ProviderRouterInterface + ProviderRouter with failover and HealthRecorder/MetricRecorder wiring.
- Aligned FailoverPlannerInterface and SlaPolicyInterface namespaces with App\\ServiceInterface\\Locator.
- Added ProviderRouterTest with basic success, failover, and all-fail scenarios.

Entry points:
- App\\ServiceInterface\\Locator\\ProviderRouterInterface
- App\\Service\\Locator\\ProviderRouter

Notes:
- Router returns provider-level payload (status, _provider, latencyMs, providerList).
- AddressResult composition stays in the higher-level pipeline.
