# Locating LC-10 — Service Batch/Location path normalization

LC-10 is a narrow App-owned service-family normalization wave.

## Scope

Only the Batch service slice is normalized:

- `src/Service/Batch/*.php` -> `src/Service/Batch/Location/*.php`
- `src/ServiceInterface/Batch/*.php` -> `src/ServiceInterface/Batch/Location/*.php`

Namespaces are already `App\Service\Batch\Location` and `App\ServiceInterface\Batch\Location`, so this wave aligns physical PSR-4 paths to the existing namespace contract.

## Non-goals

- No Smartresponsor legacy retirement.
- No Provider or Observability service movement.
- No controller, route, fixture, or test rewrite.
- No cumulative repository overwrite.

## Apply behavior

The apply script overlays the touched files and retires only the explicitly listed old Batch paths after writing timestamped backups under `.patch-backup/`.

## Validation

```bash
composer dump-autoload
composer lint
composer canon:service-family
composer canon:service-batch-location-path
composer canon:all
```
