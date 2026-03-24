# R27 — App-owned batch message-bus seam

Wave 27 introduces an App-owned batch message-bus seam so the active App batch dispatch path no longer depends on the legacy Locator bus shape directly.

## Added
- `App\InfrastructureInterface\Batch\Location\AddressBatchMessageBusInterface`
- `App\Infrastructure\Batch\Location\LegacyAddressBatchMessageBus`
- `App\Infrastructure\Batch\Location\InMemoryAddressBatchMessageBus`
- `App\Infrastructure\Batch\Location\MessageBusAddressBatchMessageDispatcher`

## Changed
- `config/services.php` now wires `AddressBatchMessageDispatcherInterface` through the App bus seam.
- `AddressBatchServiceTest` now exercises the App handler + App in-memory bus path.

## Removed
- `App\Infrastructure\Batch\Location\LegacyAddressBatchMessageDispatcher`
- legacy dispatcher test replaced by App bus seam tests

## Effect
The active dispatch chain becomes:

`App Batch Service -> App Message Dispatcher -> App Message Bus Interface -> (legacy bus adapter OR in-memory App bus)`

This keeps legacy bus translation at the edge and gives the App layer its own runtime/testable bus seam.
