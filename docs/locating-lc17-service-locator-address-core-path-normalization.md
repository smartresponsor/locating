# Locating LC-17 — Service Locator Address core path normalization

LC-17 performs the first small real normalization inside the `Smartresponsor\Service\Locator\Address` legacy cluster.

## Scope

Moved into the canonical App-owned service mirror:

- `src/Service/Locator/Address/AddressParseService.php`
- `src/Service/Locator/Address/AddressStandardizeService.php`
- `src/ServiceInterface/Locator/Address/AddressParseServiceInterface.php`
- `src/ServiceInterface/Locator/Address/AddressStandardizeServiceInterface.php`

Canonical targets:

- `src/Service/Address/Location/AddressParseService.php`
- `src/Service/Address/Location/AddressStandardizeService.php`
- `src/ServiceInterface/Address/Location/AddressParseServiceInterface.php`
- `src/ServiceInterface/Address/Location/AddressStandardizeServiceInterface.php`

## Boundary

LC-17 deliberately does not retire the whole `Smartresponsor\Service\Locator` cluster. It only removes the two nested address services and their two mirrored interfaces after creating canonical App-owned replacements.

`src/Infrastructure/Locator/Http/Kernel.php` is updated because it directly instantiated the moved services.

## Gate

Run:

```bash
composer canon:service-locator-address-core-path
```

The gate fails if the four old nested address paths are still present, if the four canonical files are missing, or if the HTTP kernel still imports the old services.
