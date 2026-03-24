# Location R10 — App infrastructure boundary extraction

This wave extracts the provider-facing infrastructure seam behind App-owned infrastructure contracts.

## Added App-owned infrastructure contracts

- `App\InfrastructureInterface\Provider\Location\AddressSuggestGatewayInterface`
- `App\InfrastructureInterface\Provider\Location\AddressReverseGatewayInterface`
- `App\InfrastructureInterface\Provider\Location\LocationMetricRecorderInterface`

## Added transition adapters

- `App\Infrastructure\Provider\Location\LegacyAddressSuggestGateway`
- `App\Infrastructure\Provider\Location\LegacyAddressReverseGateway`
- `App\Infrastructure\Provider\Location\LegacyLocationMetricRecorder`

## Effect

`App\Service\Provider\Location\...` no longer imports legacy `Smartresponsor\InfrastructureInterface\Locator\...` directly.
Legacy infrastructure remains isolated inside App transition adapters.
