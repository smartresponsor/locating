# Locating LC-13 — Service Bridge/Batch/Location path normalization

LC-13 continues the small App-owned service-family normalization track.

## Scope

Only the bridge batch service pair is touched:

- `src/Service/Bridge/Batch/LegacyAddressResultFactory.php`
- `src/ServiceInterface/Bridge/Batch/LegacyAddressResultFactoryInterface.php`

The files are moved under the physical `Location` path to match their already declared namespaces:

- `App\Service\Bridge\Batch\Location`
- `App\ServiceInterface\Bridge\Batch\Location`

## Non-goals

- No `Smartresponsor\Service\Locator` retirement.
- No controller, infrastructure, entity, or fixture rewrite.
- No repository-wide cleanup or destructive overwrite.

## Gate

Run:

```bash
composer canon:service-bridge-batch-location-path
```

The gate fails if the legacy physical paths still exist or the canonical `Location` paths are missing.
