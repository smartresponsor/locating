# Location R37 — App suggest policy ordering seam

This wave deepens the active suggest contour by replacing static source ordering with an App-owned policy seam.

## Added
- `App\ServiceInterface\Provider\Location\AddressSuggestionSourceHealthPolicyInterface`
- `App\ServiceInterface\Provider\Location\AddressSuggestionSourceQuotaPolicyInterface`
- `App\Service\Provider\Location\LegacyHealthAwareAddressSuggestionSourceHealthPolicy`
- `App\Service\Provider\Location\LegacyQuotaAwareAddressSuggestionSourceQuotaPolicy`
- `App\Service\Provider\Location\PolicyAddressSuggestionSourceOrder`

## Changed
- `App\ServiceInterface\Provider\Location\AddressSuggestionSourceInterface` now exposes `sourceKey()`.
- `App\Service\Provider\Location\LegacyAddressSuggestionProvider` now acts as a named source.
- `config/services.php` now wires policy-based ordering instead of static ordering for the active suggest runtime.

## Effect
The active suggest path now uses App-owned health/quota-aware ordering before ranking.
Legacy health/quota systems remain isolated behind App policy adapters.
