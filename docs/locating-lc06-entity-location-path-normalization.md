# Locating LC-06 — Entity Location path normalization

LC-06 is the first real Entity-family normalization patch after LC-04/LC-05 inventory.

## Scope

This wave moves root-level Location entities from generic physical folders into namespace-aligned folders:

- `src/Entity/*.php` → `src/Entity/Location/*.php`
- `src/EntityInterface/*.php` → `src/EntityInterface/Location/*.php`

The PHP namespaces are already `App\Entity\Location` and `App\EntityInterface\Location`, so this wave aligns the physical PSR-4 path with the declared namespace without changing public class names or constructor signatures.

## Why this is safe

- No service/controller/runtime references are rewritten.
- No class names are renamed.
- No namespace strings are changed for the moved files.
- The legacy `Smartresponsor\Entity\Locator\...` cluster is intentionally left untouched for a separate bridge-retirement wave.
- The apply script backs up and removes only the explicitly listed old root-level Entity/EntityInterface files.

## Moved file count

- Entity files: 32
- EntityInterface files: 31

## New gate

Run:

```bash
composer canon:entity-location-path
```

The gate fails if an `App\Entity\Location` class remains physically under `src/Entity/*.php` or if an `App\EntityInterface\Location` interface remains physically under `src/EntityInterface/*.php`.

## Next wave

LC-07 should normalize the next Entity layer by class-form and business prefix:

1. review `Smartresponsor\Entity\Locator\...` usage,
2. split true legacy bridge objects from canonical Location entities,
3. migrate tests and factories incrementally,
4. only then retire `Smartresponsor\` Entity aliases.
