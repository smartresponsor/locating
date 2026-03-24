# R43 — App provider cost ordering seam

This wave extends the active App-owned suggest/reverse provider-ordering contour with a cost-aware seam.

## Added App-owned boundaries

- `App\EntityInterface\Location\ProviderCostSignalInterface`
- `App\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface`
- `App\InfrastructureInterface\Provider\Location\ProviderCostCatalogGatewayInterface`
- `App\ServiceInterface\Provider\Location\AddressSuggestionSourceCostPolicyInterface`
- `App\ServiceInterface\Provider\Location\AddressReverseSourceCostPolicyInterface`

## Runtime effect

Both policy-driven source-order implementations now consider:

1. health
2. quota eligibility
3. cost penalty as tie-break / ordering signal

Legacy `ProviderCostCatalogInterface` is isolated behind App-owned gateway and reader adapters.
