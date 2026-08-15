# Locating LC-07 Service-family audit

LC-07 opens the service-class canonicalization track after the Entity-first path work.

This wave is intentionally non-destructive:

- it does not rename service classes;
- it does not move runtime service files;
- it does not delete legacy Smartresponsor service clusters;
- it writes machine-readable reports for the next targeted wave.

## Canonical direction

The current Symfony-oriented surface should converge toward mirrored layers:

- `src/Service/<Direction>/...`
- `src/ServiceInterface/<Direction>/...`

For App-owned Locating services, the expected namespace shape is:

- `App\\Service\\<Direction>\\Location\\...`
- `App\\ServiceInterface\\<Direction>\\Location\\...`

The legacy `Smartresponsor\\Service\\Locator\\...` cluster is measured separately and should be retired through explicit winner/bridge waves, not through broad namespace replacement.

## Reports

Run:

```bash
composer canon:service-family
```

The scanner writes:

- `report/locating-service-family-audit-latest.json`
- `report/locating-service-family-audit-latest.csv`
- `report/locating-service-family-normalization-shortlist.md`

## LC-08 handoff

LC-08 should use the shortlist to normalize a small App-owned service/interface family first. The recommended order is:

1. Address service family.
2. HTTP service family.
3. Observability service family.
4. Provider service family.
5. Smartresponsor legacy service cluster retirement only after bridge/winner confirmation.
