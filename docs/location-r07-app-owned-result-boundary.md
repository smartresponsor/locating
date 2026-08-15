# Location R07 — App-owned result boundary

This wave removes `Smartresponsor\EntityInterface\Locator\...` from the public `App\ServiceInterface\Address\Location\...` capability boundary.

## Implemented

- Added App-owned result contracts and entities:
  - `AddressSuggestionResultInterface` / `AddressSuggestionResult`
  - `AddressReverseResultInterface` / `AddressReverseResult`
- Added `LocationResultFactoryInterface` / `LocationResultFactory`
- Updated legacy capability adapters to map legacy locator entities into App-owned result entities
- Updated HTTP `LocationViewFactory` to consume App-owned result contracts instead of legacy locator entity interfaces
- Added getters to App view entities/interfaces (`label`, `status`, `providerKey`, etc.) so tests and controllers can depend on typed methods instead of implicit array structure

## Effect

The boundary is now:

`Service -> App HTTP Service -> App Capability Interface -> App Result Interface -> App Legacy Adapter -> Smartresponsor legacy`

Legacy locator entities are now isolated inside adapter/factory internals.
