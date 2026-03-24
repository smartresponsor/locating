# Locating / Location — E1-01 Legacy Inventory Overview

## Current objective
Build a full remaining-legacy map for migration completion and bridge shutdown preparation.

## Executive summary
- remaining Smartresponsor source classes: 442
- remaining Locator paths: 602
- App -> legacy direct imports: 78
- bridge blockers: 58
- forbidden roots still populated: 5

## Highest-risk areas
1. Broad `Smartresponsor\ => src/` autoload bridge still active in `composer.json`.
2. Active App runtime still imports legacy provider/infrastructure contracts directly in multiple services.
3. Large surviving forbidden roots (`src/Domain*`, `src/Contract`, `src/Model`, `src/Strategy`, `src/Integration`, `src/Bundle`) still contain operational or transitional code.

## Preliminary classification totals
- winner: 0
- adapter: 97
- duplicate: 8
- dead: 337

## Immediate next recommendations
1. Execute Winner Pack A against provider/runtime infra blockers from `app-to-legacy-import-map`.
2. Build first delete wave from duplicate/dead `Locator` paths and legacy-only tests.
3. Re-scan bridge blockers after Winner Pack A before touching `composer.json` autoload.
