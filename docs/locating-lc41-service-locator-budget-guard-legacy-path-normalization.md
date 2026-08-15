# Locating LC-41 — Service Locator BudgetGuard legacy path normalization

LC-41 moves the root-level legacy `BudgetGuard` service out of `Smartresponsor\Service\Locator` and into the canonical provider/location service layer.

## Source path

- `src/Service/Locator/BudgetGuard.php`

## Canonical path

- `src/Service/Provider/Location/BudgetGuardService.php`

## Canonical namespace

- `App\Service\Provider\Location`

## Notes

The existing bridge legacy contract remains the compatibility boundary:

- `App\Bridge\Legacy\Service\Location\BudgetGuardLegacyInterface`

The old path is retired only by the touched-file apply script, with backup under `.patch-backup`.
