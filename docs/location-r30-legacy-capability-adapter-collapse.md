# Location R30 — Legacy capability adapter collapse

This wave removes the now-orphaned transitional capability adapters:
- `App\Service\Address\Location\LegacyAddressSuggestCapability`
- `App\Service\Address\Location\LegacyAddressReverseCapability`

They were introduced as a temporary bridge while the native App-owned capability/runtime path was being extracted.
The current operational wiring in `config/services.php` aliases capability interfaces directly to:
- `App\Service\Address\Location\AddressSuggestCapability`
- `App\Service\Address\Location\AddressReverseCapability`

So the legacy capability adapters are no longer part of the active runtime graph.
