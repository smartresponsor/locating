# Locator API contract – v1

File: `openapi/locator-v1.yaml`

This OpenAPI document describes the public HTTP contract for the Locator
component (version 1).

Covered endpoints:

- `GET /locator/status`
- `GET /locator/address/suggest`
- `GET /locator/address/reverse`

Key response shapes:

- `StatusResponse` – high-level status and metrics.
- `SuggestResponse` – list of `AddressSuggestion` plus optional
  `quotaExceeded` flag.
- `AddressResult` – normalized address, validation issues and optional
  `GeoPoint`.

Versioning rules for v1:

- No breaking changes to existing paths, parameters or JSON fields.
- New fields, if added, are always optional.
- Error semantics for documented status codes remain stable.

The test `App\Tests\Locator\Contract\LocatorOpenApiPresenceTest` ensures
that the contract file exists and contains the core paths. Runtime JSON shapes
for address-related structures are additionally covered by
`Tests\Locator\Address\AddressContractTest`.
