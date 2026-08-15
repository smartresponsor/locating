# Locating LC-23 — Service Locator Address normalizer path normalization

LC-23 performs one small real normalization inside the legacy `Smartresponsor\Service\Locator` address cluster.

## Scope

Moved the compact legacy normalizer pair from the locator root into the canonical address service layer:

- `src/Service/Locator/Normalizer.php`
- `src/ServiceInterface/Locator/NormalizerInterface.php`

Canonical targets:

- `src/Service/Address/Location/AddressNormalizerService.php`
- `src/ServiceInterface/Address/Location/AddressNormalizerServiceInterface.php`

## Naming decision

The old `Normalizer` class had no ecosystem prefix and no service-form suffix. LC-23 gives it an explicit legacy service name:

- `AddressNormalizerService`
- `AddressNormalizerServiceInterface`

The existing model dependency `Smartresponsor\Model\Locator\CanonicalAddress` is intentionally preserved. Model/entity cleanup is a separate track and should not be mixed into this service-path wave.

## Explicitly out of scope

- no model-layer migration
- no existing `AddressNormalizer` replacement
- no full `Smartresponsor\Service\Locator` retirement
- no repository-wide cleanup script

## Validation

Run:

```bash
composer dump-autoload
composer lint
composer canon:service-locator-address-normalizer-path
composer canon:service-locator-address-references
composer canon:all
```
