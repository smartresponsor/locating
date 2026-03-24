# Locating / Location — wave r02 composer + command canon

## What changed
- Canonical package identity moved to `locating/location`.
- Canonical application type moved to `project`.
- Runtime PHP requirement moved to `^8.4`.
- Added canonical root namespace target `App\\` in Composer autoload.
- Kept temporary `Smartresponsor\\` compatibility autoload only as an interim migration bridge for the next cumulative waves.
- Evacuated forbidden `src/Console` root.
- Introduced canonical command tail under `src/Command/Location/`.

## Why this wave is intentionally transitional
A direct repository-wide rename from `Smartresponsor\\* / Locator` to `App\\* / Location` would be too large and too risky for a single blind wave on the current slice. This wave establishes:

1. canonical Composer responsibility;
2. first executable `App\\` production classes inside `src/`;
3. removal of one forbidden root (`src/Console`);
4. a controlled bridge for the next namespace migration waves.

## Next wave targets
- expand `App\\` classes from command surface into controller/service/entity vertical slices;
- remove temporary `Smartresponsor\\` compatibility autoload after enough production code is migrated;
- start replacing `Locator` tails with `Location` where allowed by the protocol;
- continue evacuating forbidden roots (`Contract`, `Domain`, `Model`, `Strategy`, `Integration`, `Bundle`).
