# Locating LC-33 — Service Locator ProviderOrder legacy path normalization

LC-33 continues the provider-side Locator retirement track after the provider implementation and provider router waves.

## Scope

This wave moves the provider ordering service out of the generic legacy `Smartresponsor\Service\Locator` namespace into the canonical provider service layer.

## Touched runtime move

- `src/Service/Locator/ProviderOrder.php`
- `src/Service/Provider/Location/ProviderOrderService.php`

The class keeps the existing bridge contract:

- `App\Bridge\Legacy\Service\Location\ProviderOrderLegacyInterface`

## Temporary collaborator imports

`ScoreEnsemble` and `HealthEwma` still remain in the legacy locator namespace, so this wave explicitly imports them from `Smartresponsor\Service\Locator`. Their own relocation belongs to later focused waves.

## Rule

The old path must not remain after the touched-file overlay has been applied. The apply script retires only the explicitly listed old file and stores a backup under `.patch-backup`.
