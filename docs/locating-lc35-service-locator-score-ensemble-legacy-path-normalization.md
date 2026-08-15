# Locating LC-35 — Service Locator ScoreEnsemble legacy path normalization

LC-35 continues the provider-side Locator retirement track after `HealthEwmaService`.

## Scope

This wave moves the provider scoring ensemble out of the generic legacy locator namespace into the canonical provider service layer.

## Touched runtime move

- `src/Service/Locator/ScoreEnsemble.php`
- `src/Service/Provider/Location/ScoreEnsembleService.php`

## Touched mirrored contract move

- `src/ServiceInterface/Locator/ScoreEnsembleInterface.php`
- `src/ServiceInterface/Provider/Location/ScoreEnsembleServiceInterface.php`

## Updated direct consumer

- `src/Service/Provider/Location/ProviderOrderService.php`

## Rule

The old service and interface paths must not remain after the touched-file overlay has been applied. The apply script retires only the explicitly listed old files and stores backups under `.patch-backup`.
