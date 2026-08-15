# Locating LC-34 — Service Locator HealthEwma legacy path normalization

LC-34 continues the provider-side Locator retirement track after `ProviderOrderService`.

## Scope

This wave moves the health scoring EWMA collaborator out of the generic legacy locator namespace into the canonical provider service layer.

## Touched runtime move

- `src/Service/Locator/HealthEwma.php`
- `src/Service/Provider/Location/HealthEwmaService.php`

The class keeps the existing bridge contract:

- `App\Bridge\Legacy\Service\Location\HealthEwmaLegacyInterface`

## Updated direct consumers

- `src/Service/Provider/Location/ProviderOrderService.php`
- `src/Service/Locator/AdaptiveOrdering.php`
- `src/Service/Locator/RouterOrchestrator.php`

## Rule

The old path must not remain after the touched-file overlay has been applied. The apply script retires only the explicitly listed old file and stores a backup under `.patch-backup`.
