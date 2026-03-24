# E2-13 / Provider orchestration contract namespace retirement

This wave retires a bounded `Smartresponsor` service-interface cluster around provider orchestration.

Retired contracts:
- ProviderRouterInterface
- StrategyRegistryInterface
- ProviderOrderInterface
- AdaptiveProviderOrderInterface

Replacement bridge contracts live under `App\Bridge\Legacy\Service\Location\...`.
