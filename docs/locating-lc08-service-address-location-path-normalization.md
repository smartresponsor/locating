# Locating LC-08 — Service Address/Location path normalization

LC-08 is the first small App-owned Service-family normalization wave.

## Scope

This wave moves only the Address service slice into physical paths that match the namespaces already declared by the files:

- `App\Service\Address\Location\*`
- `App\ServiceInterface\Address\Location\*`

The legacy `Smartresponsor\Service\Locator\*` cluster remains outside this wave and is tracked separately by the service-family audit.

## Retired paths

The root-level `src/Service/Address/*.php` and `src/ServiceInterface/Address/*.php` files are retired only by exact path through the apply script, with backups under `.patch-backup/`.

## Validation

Run:

```bash
composer dump-autoload
composer lint
composer canon:service-family
composer canon:service-address-location-path
composer canon:all
```

LC-08 intentionally does not rewrite runtime references outside the touched Address service family.
