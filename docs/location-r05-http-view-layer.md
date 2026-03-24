# Locating r05 — HTTP view-layer isolation

Wave `05` deepens the new `App\...` vertical by introducing canonical application-owned view DTOs under `src/Entity/Location/...` and a dedicated mapper/factory in `App\Service\Http\Location`.

## Delivered
- `App\Entity\Location\AddressView`
- `App\Entity\Location\AddressSuggestionView`
- `App\Entity\Location\AddressReverseView`
- matching `App\EntityInterface\Location\...`
- `App\Service\Http\Location\LocationViewFactory`
- updated HTTP service adapters to return application-owned DTOs instead of raw arrays
- updated controllers to serialize DTOs at the edge
- new tests for DTO normalization and legacy-to-App view mapping

## Why this wave matters
Before `05`, the new HTTP layer still depended on array payloads synthesized directly from legacy `Smartresponsor` objects. That kept the controller contract coupled to legacy `toArray()` structure.

After `05`, the controller contract is owned by `App\...` objects. Legacy types are now contained inside the service adapter + mapper boundary.

## Remaining debt
- core locator business logic still lives under `Smartresponsor\...`
- forbidden roots (`src/Bundle`, `src/Contract`, `src/Domain`, `src/DomainInterface`, `src/Integration`, `src/Model`, `src/Strategy`) remain to be evacuated
- package still carries temporary `Smartresponsor\ => src/` compatibility bridge in `composer.json`
