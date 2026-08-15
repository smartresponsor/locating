# Location R03 — App HTTP vertical and command path correction

This wave continues from the previous cumulative slice only.

## Done

- removed the shallow `src/Command/Location/...` path introduced in the previous wave
- relocated the CLI entrypoints into safer non-forbidden production roots:
  - `src/Command/Address/LocationNormalizeCommand.php`
  - `src/Command/Geo/LocationReverseCommand.php`
- introduced the first `App\...` HTTP controller vertical in a depth-safe location tail:
  - `src/Service/Http/Location/...`
  - `src/ServiceInterface/Http/Location/...`
- tightened the canon gate so `Location` tails under `src` now require deeper placement (`src/.../.../Location/...`) except for `src/Entity/Location/...`

## Why this wave matters

The repository still contains a large `Smartresponsor\...` body and several forbidden roots, but this wave starts a real application-facing migration path without reinforcing shallow forbidden tails.

## Remaining large violations

- `Smartresponsor\` remains the dominant namespace across the legacy body
- forbidden roots still present: `src/Bundle`, `src/Contract`, `src/Domain`, `src/DomainInterface`, `src/Integration`, `src/Model`, `src/Strategy`
- test tree still carries `Locator` naming and layout debt

## Next target

Wave 04 should start moving one bounded business vertical from `Smartresponsor\Service\Locator` and related service wiring toward `App\...` with matching service-interface bridges or replacements.
