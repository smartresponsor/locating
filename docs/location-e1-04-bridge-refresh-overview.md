# Locating / Location — E1-04 Bridge Refresh Overview

## Current objective
Refresh bridge blockers after E1-02/E1-03 bridge squeeze and validate first duplicate/dead purge shortlist.

## Executive summary
- remaining Smartresponsor classes: 442
- remaining Locator paths: 602
- App -> legacy direct imports: 41
- bridge blockers: 42
- forbidden roots still populated: 4

## Highest-risk areas
1. App factories and bridge contracts still typehint legacy Locator entities/results.
2. `config/services.php` still names multiple legacy contracts and concretes.
3. Forbidden roots `src/Integration`, `src/Contract`, `src/Strategy`, `src/Model` still contain live namespace surface.

## Preliminary classification totals
- winner: 2
- adapter: 20
- duplicate: 2
- dead: 422

## Immediate next recommendations
1. Rehome batch/result entity typehints out of App contracts and factories.
2. Wrap `AddressQuotaGuardInterface` behind an App-owned backend seam.
3. Purge validated dead strategy classes and legacy bundle bootstrap after config check.
