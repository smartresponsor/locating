# Locating LC-22 — Service Locator AddressQuotaGuard path normalization

LC-22 performs one small real normalization inside the legacy `Smartresponsor\Service\Locator` address cluster.

## Scope

Moved the quota guard service from the legacy locator root:

- `src/Service/Locator/AddressQuotaGuard.php`
- to `src/Service/Address/Location/AddressQuotaGuard.php`

Updated the direct HTTP backend consumer:

- `src/Service/Http/Location/SmartresponsorLocationQuotaGuardBackend.php`

## Canonical result

- `App\Service\Address\Location\AddressQuotaGuard`

The class name already has a valid service-form suffix, `Guard`, so LC-22 does not rename the class.

## Explicitly out of scope

- no provider quota migration
- no tenant quota migration
- no full `Smartresponsor\Service\Locator` retirement
- no repository-wide cleanup script

## Validation

Run:

```bash
composer dump-autoload
composer lint
composer canon:service-locator-address-quota-guard-path
composer canon:service-locator-address-references
composer canon:all
```
