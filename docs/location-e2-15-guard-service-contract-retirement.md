# E2-15 — Guard service contract namespace retirement

This wave retires a bounded legacy service-interface cluster around budget, canary, and degrade helpers.

Retired legacy interfaces:
- `Smartresponsor\Domain\Locator\BudgetGuardInterface`
- `Smartresponsor\Domain\Locator\CanaryGuardInterface`
- `Smartresponsor\Domain\Locator\CanaryToggleInterface`
- `Smartresponsor\Domain\Locator\DegradeManagerInterface`

Replacement App-owned bridge contracts:
- `App\Bridge\Legacy\Service\Location\BudgetGuardLegacyInterface`
- `App\Bridge\Legacy\Service\Location\CanaryGuardLegacyInterface`
- `App\Bridge\Legacy\Service\Location\CanaryToggleLegacyInterface`
- `App\Bridge\Legacy\Service\Location\DegradeManagerLegacyInterface`

Bounded legacy implementations were re-pointed to the new bridge contracts without broad namespace renames.
