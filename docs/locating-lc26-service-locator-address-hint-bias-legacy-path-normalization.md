# Locating LC-26 — Service Locator Address hint-bias legacy path normalization

LC-26 retires the generic `Smartresponsor\Service\Locator\HintBias` pair into the canonical App-owned address service layer.

## Canonical move

- `src/Service/Locator/HintBias.php`
  → `src/Service/Address/Location/AddressHintBiasService.php`
- `src/ServiceInterface/Locator/HintBiasInterface.php`
  → `src/ServiceInterface/Address/Location/AddressHintBiasServiceInterface.php`

The `LegacyService` suffix is intentional because LC-21 already created the stronger `AddressHintBiasService` name for the explicit address-specific implementation.

## Safety

The apply script retires only the two exact legacy paths listed above and stores backups under `.patch-backup/locating-lc26/` before deletion.
