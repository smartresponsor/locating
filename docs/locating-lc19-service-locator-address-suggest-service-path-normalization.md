# Locating LC-19 — Service Locator AddressSuggest service path normalization

LC-19 performs one small real normalization inside the legacy `Smartresponsor\Service\Locator` address cluster.

## Scope

Moved and renamed:

- `src/Service/Locator/AddressSuggest.php`
- to `src/Service/Address/Location/AddressSuggestLegacyService.php`

The new class keeps the legacy bridge contract but receives a canonical service suffix:

- `App\Service\Address\Location\AddressSuggestLegacyService`
- implements `App\Bridge\Legacy\Service\Location\AddressSuggestLegacyServiceInterface`

## Explicitly out of scope

- no provider cluster migration
- no bridge interface retirement
- no mass `Smartresponsor\Service\Locator` move
- no repository-wide cleanup script

## Validation

Run:

```bash
composer dump-autoload
composer lint
composer canon:service-locator-address-suggest-service-path
composer canon:service-locator-address-references
composer canon:all
```
