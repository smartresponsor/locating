# E1-06 — Adapter Squeeze A (batch legacy construction)

## Objective
Squeeze remaining batch/result bridge adapters so App services no longer construct legacy Smartresponsor entities directly.

## What changed
- Added `AddressBatchJobRecordFactoryBackendInterface` and `SmartresponsorAddressBatchJobRecordFactoryBackend`.
- Added `AddressBatchLegacyResultBackendInterface` and `SmartresponsorAddressBatchLegacyResultBackend`.
- `LegacyAddressBatchJobStore` now creates batch job records through an App-owned backend seam instead of instantiating `Smartresponsor\Entity\Locator\AddressBatchJob` directly.
- `LegacyAddressResultFactory` now maps App result state to normalized scalar arrays and delegates legacy record construction to an App-owned backend seam.

## Effect
This narrows direct legacy imports in App runtime code and keeps Smartresponsor concrete entity creation at the edge only.
