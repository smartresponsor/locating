# Locating LC-09 — Service Http/Location path normalization

LC-09 is the second small App-owned Service-family normalization wave.

## Scope

This wave moves only the Http service slice into physical paths that match the namespaces already declared by the files:

- `App\Service\Http\Location\*`
- `App\ServiceInterface\Http\Location\*`

The legacy `Smartresponsor\Service\Locator\*` cluster remains outside this wave and is tracked separately by the service-family audit.

## Retired paths

The root-level `src/Service/Http/*.php` and `src/ServiceInterface/Http/*.php` files are retired only by exact path through the apply script, with backups under `.patch-backup/`.

## Validation

Run:

```bash
composer dump-autoload
composer lint
composer canon:service-family
composer canon:service-http-location-path
composer canon:all
```

LC-09 intentionally does not rewrite runtime references outside the touched Http service family.
