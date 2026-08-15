# Locating LC-28 — Service Locator Provider Here legacy path normalization

LC-28 moves the legacy HERE provider out of `Smartresponsor\Service\Locator\Provider` and into the canonical Symfony-oriented provider service layer.

## Normalized files

- `src/Service/Locator/Provider/HereProvider.php`
  - retired with backup by the apply script
- `src/Service/Provider/Location/HereProviderService.php`
  - canonical service location

## Consumer alignment

`src/Service/Locator/Provider/ProviderRouter.php` now imports and instantiates `App\Service\Provider\Location\HereProviderService`.

## Why `LegacyService`

The implementation still preserves the legacy HERE provider behavior and bridge contract. The `LegacyService` suffix makes the transitional status explicit while placing it under the canonical service layer.

## Gate

Run:

```bash
composer canon:service-locator-provider-here-legacy-path
```

The gate verifies that the old path is retired, the canonical path exists, and `ProviderRouter` no longer instantiates the old class.