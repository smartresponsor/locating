# Locating LC-20 — Service Locator AddressCanonicalizer path normalization

LC-20 performs one small real normalization inside the legacy `Smartresponsor\Service\Locator` address cluster.

## Scope

Moved and renamed:

- `src/Service/Locator/AddressCanonicalizer.php`
- to `src/Service/Address/Location/AddressCanonicalizerService.php`

The new class keeps the legacy helper contract but receives a canonical service suffix:

- `App\Service\Address\Location\AddressCanonicalizerService`
- implements `App\Bridge\Legacy\Helper\Location\AddressCanonicalizerInterface`

## Explicitly out of scope

- no provider cluster migration
- no bridge helper interface retirement
- no mass `Smartresponsor\Service\Locator` move
- no repository-wide cleanup script

## Validation

Run:

```bash
composer dump-autoload
composer lint
composer canon:service-locator-address-canonicalizer-path
composer canon:service-locator-address-references
composer canon:all
```
