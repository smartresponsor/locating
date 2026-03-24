# Location R25 — App message boundary extraction

This wave extracts the active batch message path into App-owned message and handler surfaces while preserving the legacy locator bus as a transitional adapter boundary.

## Added
- `App\MessageInterface\Batch\Location\AddressBatchMessageInterface`
- `App\Message\Batch\Location\AddressBatchMessage`
- `App\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface`
- `App\MessageHandler\Batch\Location\AddressBatchMessageHandler`

## Changed
- `Smartresponsor\MessageHandler\Locator\AddressBatchMessageHandler` now acts as a thin adapter that forwards legacy locator messages into the App-owned handler.
- `config/services.php` now wires the App handler explicitly and keeps the legacy handler as a compatibility adapter.

## Outcome
The batch runtime is now App-owned on the message-handling path while the legacy message bus remains intact for compatibility.
