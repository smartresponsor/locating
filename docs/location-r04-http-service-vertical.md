# Location r04 — HTTP service vertical adapters

## What changed

Wave 04 deepens the new `App\...` HTTP vertical beyond controllers.

Added `App\ServiceInterface\Http\Location\...` and `App\Service\Http\Location\...` adapters so the new controllers no longer depend directly on the legacy `Smartresponsor\Service...` layer.

## Defect fixed

`LocationAddressReverseHttpService` from the previous wave referenced `AddressQuotaGuard::OPERATION_REVERSE`, but the legacy guard defines `OPERATION_GEOCODE` and `OPERATION_SUGGEST` only.

Wave 04 fixes that by introducing `LocationQuotaGuardInterface::OPERATION_REVERSE` in the new `App\...` layer and mapping it to the legacy geocode quota operation inside the adapter.

## Scope

This wave is intentionally bounded:

- new App-layer service contracts for suggest/reverse/quota guard
- adapters that bridge to existing Smartresponsor services
- controllers switched to App-layer contracts
- regression tests for the new controller behavior and quota-operation mapping

## Remaining debt

The underlying service/entity/infrastructure graph still largely lives under `Smartresponsor\...` and several forbidden roots are still present under `src/`.
