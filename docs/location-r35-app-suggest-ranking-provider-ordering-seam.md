# Location R35 - App Suggest Ranking and Provider Ordering Seam

This wave deepens the live suggest contour instead of collapsing more orphan clusters.

## Delivered seam

The active `App` suggest path now owns:

- source boundary: `AddressSuggestionSourceInterface`
- source ordering boundary: `AddressSuggestionSourceOrderInterface`
- ranking boundary: `AddressSuggestionRankerInterface`
- orchestrating provider: `OrderedAddressSuggestionProvider`

## Effect

`AddressSuggestCapability` still depends on `App\ServiceInterface\Provider\Location\AddressSuggestionProviderInterface`, but that provider is no longer the raw gateway adapter.

The runtime path is now:

`AddressSuggestCapability -> OrderedAddressSuggestionProvider -> ordered App sources -> App ranker -> App suggestion results`

The legacy gateway adapter remains only as one source implementation:

- `LegacyAddressSuggestionProvider`

This keeps the current runtime stable while moving ranking/provider-ordering into an App-owned seam.
