# Locating LC-16 — Service Locator address reference audit

LC-16 maps dependencies for the address-focused `Smartresponsor\Service\Locator` legacy slice before any physical move, namespace retirement, or class rename.

## Scope

LC-16 reads the symbol inventory from these legacy producers:

- `src/Service/Locator/Address/**`
- `src/ServiceInterface/Locator/Address/**`
- root-level `src/Service/Locator/*Address*.php`
- root-level `src/ServiceInterface/Locator/*Address|Suggest|Ranker|Normalizer*.php`

Then it scans `src/**` for FQCN imports, inline FQCN usage, and short imported class names.

## Why this wave is report-only

The address legacy slice is not safe to move until imports and type references are known. LC-16 produces the reference map needed for a small LC-17 touched-file normalization wave.

## Generated reports

Run:

```bash
composer canon:service-locator-address-references
```

Generated files:

- `report/locating-service-locator-address-reference-audit-latest.json`
- `report/locating-service-locator-address-reference-audit-latest.csv`
- `report/locating-service-locator-address-reference-map.md`

## Next wave

LC-17 should choose the lowest-reference address candidates and normalize a very small subset. Rename-sensitive classes should be handled after their import/reference blast radius is clear.
