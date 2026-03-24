# E1-10 / Command de-legacy and readiness

## Objective
Remove the last non-adapter Smartresponsor references from App console commands and re-run bridge squeeze readiness.

## Changes
- `App\Command\Address\LocationNormalizeCommand` now depends on `App\ServiceInterface\Address\Location\AddressPipelineInterface`.
- `App\Command\Geo\LocationReverseCommand` now depends on `App\ServiceInterface\Http\Location\AddressReverseServiceInterface`.
- Removed direct usage of `Smartresponsor\Service\Locator\LocatorService`.
- Removed direct usage of `Smartresponsor\Model\Locator\CanonicalAddress`.
- Removed direct usage of `Smartresponsor\Model\Locator\GeoPoint`.

## Result
After this wave, residual bridge blockers should be reduced to intentional edge adapters only.
