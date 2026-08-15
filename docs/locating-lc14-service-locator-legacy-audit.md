# Locating LC-14 — Service Locator legacy audit

LC-14 opens the legacy `Smartresponsor\Service\Locator` retirement track without moving or renaming the large legacy cluster yet.

## Scope

- `src/Service/Locator/**`
- `src/ServiceInterface/Locator/**`
- report-only classification for legacy namespace, declaration kind, form suffix, and `LegacyInterface` coupling

## Why this wave is report-only

The legacy Locator cluster is much larger than the App-owned service slices already normalized in LC-08 through LC-13. Moving it in one pass would combine namespace migration, service contract migration, and physical path migration into a single high-risk patch.

LC-14 creates a machine-readable shortlist first so the next waves can retire or normalize subfamilies one at a time.

## Generated reports

Run:

```bash
composer canon:service-locator-legacy
```

Generated files:

- `report/locating-service-locator-legacy-audit-latest.json`
- `report/locating-service-locator-legacy-audit-latest.csv`
- `report/locating-service-locator-legacy-shortlist.md`

## Next waves

Recommended order:

1. Normalize the smallest Locator subfamily with clear suffixes and no external blockers.
2. Split `LegacyInterface` contracts into explicit Symfony-oriented `ServiceInterface/Locator/Location` forms.
3. Retire or bridge remaining `Smartresponsor\...` contracts only with exact touched-file backup lists.
