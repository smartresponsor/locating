# Locating / Location — E1-09 Bridge Squeeze Review

## Current objective
Refresh bridge-blocker picture after E1-05…E1-08 and decide whether bridge can be narrowed aggressively.

## Executive summary
- remaining Smartresponsor classes: 427
- App files with legacy refs: 17
- edge-adapter App files: 15
- residual non-adapter bridge blockers: 2
- forbidden roots still populated: 3

## Key conclusion
The bridge is no longer broadly required by the active App runtime. Most remaining App→legacy references are concentrated in intentional edge adapters. The main residual non-adapter blockers are the two console commands and a small set of Smartresponsor-backed record wrappers that still exist only to bridge batch/result edges.

## Immediate next recommendations
1. Rehome the two console commands off `Smartresponsor\Service\Locator\LocatorService` and legacy `Model\Locator` shapes.
2. Validate whether Smartresponsor-backed record wrappers can be collapsed into App-owned concrete records.
3. Run one more blocker refresh after those two moves, then prepare composer bridge squeeze/shutdown review.
