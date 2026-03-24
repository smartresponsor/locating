# Locating R06 — capability boundary extraction

This wave moves the new `App\Service\Http\Location\...` layer away from direct constructor-level coupling to legacy `Smartresponsor\ServiceInterface\Locator\...` contracts.

## Added App capability boundary

- `App\ServiceInterface\Address\Location\AddressSuggestCapabilityInterface`
- `App\ServiceInterface\Address\Location\AddressReverseCapabilityInterface`
- `App\Service\Address\Location\LegacyAddressSuggestCapability`
- `App\Service\Address\Location\LegacyAddressReverseCapability`

## Effect

`App\Service\Http\Location\AddressSuggestService` and `AddressReverseService` now depend on App-owned capability interfaces.
Legacy `Smartresponsor` contracts are contained behind App adapters instead of leaking into the HTTP service constructor boundary.
