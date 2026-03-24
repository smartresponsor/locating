# E2-12 / Provider service contract namespace retirement

This wave retires a bounded provider service contract cluster from `Smartresponsor\ServiceInterface\Locator` into `App\Bridge\Legacy\Service\Location`.

## Retired contracts
- `GeocodeProviderInterface`
- `HereProviderInterface`
- `MapboxProviderInterface`
- `NominatimProviderInterface`

## Rehomed bridge contracts
- `GeocodeProviderLegacyInterface`
- `HereProviderLegacyInterface`
- `MapboxProviderLegacyInterface`
- `NominatimProviderLegacyInterface`

## Updated implementations
- `HereProvider`
- `MapboxProvider`
- `NominatimProvider`
