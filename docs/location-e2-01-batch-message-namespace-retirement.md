# Locating / Location — E2-01 Batch Message Namespace Retirement

## Objective
Retire the remaining `Smartresponsor\Message\Locator` and `Smartresponsor\MessageHandler\Locator` batch message surface from the active runtime path.

## What changed
- Introduced App-owned legacy-bridge batch message classes under `App\Bridge\Legacy\Batch\Location`
- Rewired the legacy batch message bus backend to use the App bridge message contract
- Rewired service configuration to point the legacy bus handler surface to the App bridge handler
- Removed the old `Smartresponsor\Message\Locator\AddressBatchMessage` and `Smartresponsor\MessageHandler\Locator\AddressBatchMessageHandler`
- Removed the old `Smartresponsor\InfrastructureInterface\Locator\AddressBatchMessageBusInterface`

## Effect
The active batch bridge path no longer depends on any remaining `Smartresponsor\Message*` declarations.
Legacy batch dispatch is still supported, but the compatibility surface now lives entirely under `App\Bridge\Legacy\...`.
