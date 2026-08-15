# Locating LC-05 — Entity-family map

LC-05 is the first Entity-first hardening wave after the LC-04 inventory baseline.

It intentionally remains report-only because the current slice contains both root Entity files and nested `Entity/Locator` files, plus matching and missing `EntityInterface` contracts. Moving or renaming those classes before a family map would create unnecessary runtime risk.

## Added checks

- Maps `src/Entity/**` and `src/EntityInterface/**` into logical Entity families.
- Detects Entity-only families.
- Detects EntityInterface-only families.
- Detects duplicate logical families across root and nested Entity buckets.
- Flags non-`App\\` namespace drift inside Entity and EntityInterface layers.
- Flags Doctrine table names that do not start with the canonical `location_` prefix.
- Emits JSON, CSV, and Markdown shortlist reports under `report/`.

## Commands

```bash
composer canon:entity-family
composer canon:all
```

## Next wave

LC-06 should use `report/locating-entity-family-normalization-shortlist.md` and apply a deliberately small touched-file rename/move set. The first safe target should be a low-coupling family with one Entity and one EntityInterface or an obviously duplicate legacy family whose usage scan is clean.
