# E2-05 — service contract namespace retirement

Retired remaining `Smartresponsor\ServiceInterface\Locator` contracts for the bounded address service cluster.

## Retired interfaces
- `AddressSuggestInterface`
- `AddressReverseInterface`
- `AddressQuotaGuardInterface`

## Replacement bridge contracts
- `App\Bridge\Legacy\Service\Location\AddressSuggestLegacyServiceInterface`
- `App\Bridge\Legacy\Service\Location\AddressReverseLegacyServiceInterface`
- `App\Bridge\Legacy\Service\Location\AddressQuotaGuardLegacyServiceInterface`

## Scope
This wave keeps runtime behavior unchanged while moving the compatibility seam under `App\Bridge\Legacy`.
