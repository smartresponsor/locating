# Locating LC-21 — Service Locator AddressHintBias path normalization

LC-21 performs one small real normalization inside the legacy `Smartresponsor\Service\Locator` address cluster.

## Scope

Moved and renamed:

- `src/Service/Locator/AddressHintBias.php`
- to `src/Service/Address/Location/AddressHintBiasService.php`

Moved and renamed mirrored contract:

- `src/ServiceInterface/Locator/AddressHintBiasInterface.php`
- to `src/ServiceInterface/Address/Location/AddressHintBiasServiceInterface.php`

Updated the direct consumer:

- `src/Service/Locator/AdaptiveOrdering.php`

## Canonical result

- `App\Service\Address\Location\AddressHintBiasService`
- `App\ServiceInterface\Address\Location\AddressHintBiasServiceInterface`

## Explicitly out of scope

- no full adaptive-ordering migration
- no provider cluster migration
- no mass `Smartresponsor\Service\Locator` move
- no repository-wide cleanup script

## Validation

Run:

```bash
composer dump-autoload
composer lint
composer canon:service-locator-address-hint-bias-path
composer canon:service-locator-address-references
composer canon:all
```
