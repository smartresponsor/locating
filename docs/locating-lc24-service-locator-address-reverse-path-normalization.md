# Locating LC-24 — Service Locator AddressReverse path normalization

LC-24 continues the small, touched-file-only retirement of the legacy `Smartresponsor\Service\Locator` address cluster.

## Scope

Moved one legacy service from the root locator namespace into the Symfony-oriented address service layer:

- `src/Service/Locator/AddressReverse.php`
- `src/Service/Address/Location/AddressReverseLegacyService.php`

The class was renamed from `AddressReverse` to `AddressReverseLegacyService` so the file and class have an explicit service-form suffix while the existing bridge contract remains intact.

## Non-goals

- No full repository cleanup.
- No mass namespace rewrite.
- No provider/infrastructure modernization.
- No entity bridge normalization.

## Gate

Run:

```bash
composer canon:service-locator-address-reverse-path
```

The gate verifies that the old legacy path is retired, the canonical service exists, the namespace is `App\Service\Address\Location`, and no direct legacy FQCN reference to `Smartresponsor\Service\Locator\AddressReverse` remains.
