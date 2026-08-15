# Locating LC-29 — Service Locator Provider Mapbox legacy path normalization

LC-29 continues the Provider-side retirement of the legacy `Smartresponsor\Service\Locator\Provider` cluster.

## Scope

Touched runtime files:

- `src/Service/Provider/Location/MapboxProviderService.php`
- `src/Service/Locator/Provider/ProviderRouter.php`

Retired by the apply script with backup:

- `src/Service/Locator/Provider/MapboxProvider.php`

## Canonical target

The Mapbox provider now lives under the Symfony-oriented service layer:

- namespace: `App\Service\Provider\Location`
- class: `MapboxProviderService`

The `LegacyService` suffix is intentional. This is a compatibility-preserving move of the old provider implementation, not a full provider redesign.
