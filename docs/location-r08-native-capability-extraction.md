# Location R08 native capability extraction

This wave performs the first real extraction of runtime behavior out of the legacy
`Smartresponsor\ServiceInterface\Locator\...` service layer and into `App\...`.

## What changed

- Added `App\Service\Address\Location\AddressSuggestCapability`
- Added `App\Service\Address\Location\AddressReverseCapability`
- These classes implement the existing App-owned capability contracts directly
- Suggest flow now orchestrates `AddressSuggestProviderInterface` providers directly
- Reverse flow now orchestrates `ReverseHttpClientInterface` and `MetricRecorderInterface` directly

## Why this matters

Previous waves created boundaries but still relied on legacy locator service
contracts as the primary runtime implementation. This wave reduces bridge depth:

- Before: `App HTTP Service -> App Capability Interface -> Legacy adapter -> Smartresponsor service interface`
- Now possible: `App HTTP Service -> App Capability Interface -> App native capability -> infrastructure`

The legacy adapters remain in the slice for transition safety, but App now owns a
real executable implementation for both suggest and reverse capabilities.
