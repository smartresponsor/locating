# Locating LC-15 — Service Locator address legacy audit

LC-15 narrows the large `Smartresponsor\Service\Locator` retirement track to address-focused classes and interfaces.

## Scope

- `src/Service/Locator/Address/**`
- root-level `src/Service/Locator/*Address*.php` candidates
- `src/ServiceInterface/Locator/Address/**`
- root-level address/suggest/ranker/normalizer candidates in `src/ServiceInterface/Locator/**`

## Why this wave is report-only

The address slice mixes runtime services, suggestion/ranking capabilities, normalization logic, and legacy interfaces. A physical move from `Smartresponsor\...` to `App\...` should not happen before exact rename and reference plans are generated.

LC-15 produces the migration shortlist for the next small touched-file patch.

## Generated reports

Run:

```bash
composer canon:service-locator-address-legacy
```

Generated files:

- `report/locating-service-locator-address-legacy-audit-latest.json`
- `report/locating-service-locator-address-legacy-audit-latest.csv`
- `report/locating-service-locator-address-legacy-shortlist.md`

## Next wave

LC-16 should normalize the lowest-risk address candidates first, preferably classes already ending with `Service` or `Interface`, before touching ambiguous classes such as `AddressSuggest`, `AddressReverse`, `Normalizer`, or ranking helpers.
