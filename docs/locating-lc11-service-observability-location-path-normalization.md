# Locating LC-11 — Service Observability/Location path normalization

LC-11 continues the small App-owned Service-family normalization sequence after
LC-08 Address, LC-09 Http, and LC-10 Batch.

## Scope

This wave normalizes only the Observability service slice whose PHP namespaces
already declare the `Location` segment:

- `App\Service\Observability\Location\*`
- `App\ServiceInterface\Observability\Location\*`

The physical files are moved under matching directories:

- `src/Service/Observability/Location/*`
- `src/ServiceInterface/Observability/Location/*`

## Non-scope

LC-11 intentionally does not touch:

- `Smartresponsor\Service\Locator\*`
- Provider service normalization
- Bridge service normalization
- Service, Infrastructure, Integration, Message, or test trees

Those remain separate waves so each patch has a small rollback surface.

## Gate

Run:

```bash
composer canon:service-observability-location-path
composer canon:all
```

The dedicated gate writes:

- `report/locating-service-observability-location-path-audit-latest.json`
