# Locating LC-30 — Service Locator Provider Nominatim legacy path normalization

LC-30 continues the Provider-side retirement track for the legacy `Smartresponsor\Service\Locator\Provider` cluster.

## Scope

- Moves `src/Service/Locator/Provider/NominatimProvider.php` to `src/Service/Provider/Location/NominatimProviderService.php`.
- Preserves the legacy bridge contract `App\Bridge\Legacy\Service\Location\NominatimProviderLegacyInterface`.
- Updates `ProviderRouter` to instantiate `NominatimProviderService`.
- Retires only the exact old provider file through the apply script with backup.

## Out of scope

- No full Locator cleanup.
- No destructive repository overwrite.
- No provider behavior rewrite.
