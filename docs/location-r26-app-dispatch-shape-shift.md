# Location R26 — App dispatch shape shift

## Summary
Wave 26 moves the batch dispatch shape fully into the App-owned message boundary.

## Changes
- `AddressBatchMessageDispatcherInterface` now dispatches `App\MessageInterface\Batch\Location\AddressBatchMessageInterface`
- `AddressBatchService` now creates and dispatches `App\Message\Batch\Location\AddressBatchMessage`
- `LegacyAddressBatchMessageDispatcher` is reduced to a translation adapter from App message to legacy `Smartresponsor\Message\Locator\AddressBatchMessage`

## Result
The batch dispatch path is now:

`App Batch Service -> App AddressBatchMessage -> App dispatcher interface -> legacy bus adapter -> legacy bus`

This removes the old `dispatch(string $jobId, array $payload)` shape from the App boundary and keeps the legacy message only inside the adapter.
