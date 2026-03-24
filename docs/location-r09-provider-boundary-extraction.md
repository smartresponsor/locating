# Location R09 — Provider boundary extraction

This wave moves the direct provider-facing dependency out of `App\Service\Address\Location\...Capability` classes.

## What changed

- Introduced App-owned provider contracts:
  - `App\ServiceInterface\Provider\Location\AddressSuggestionProviderInterface`
  - `App\ServiceInterface\Provider\Location\AddressReverseProviderInterface`
- Introduced transition adapters that isolate legacy infrastructure dependencies:
  - `App\Service\Provider\Location\LegacyAddressSuggestionProvider`
  - `App\Service\Provider\Location\LegacyAddressReverseProvider`
- Reduced capability implementations to App-owned orchestration delegates.

## Effect

`App` capabilities no longer import `Smartresponsor\InfrastructureInterface\Locator\...` directly.
Those legacy infrastructure interfaces are now isolated behind App-owned provider contracts.
