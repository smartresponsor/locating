# R28 — App-owned in-memory batch runtime path

This wave adds a fully App-owned in-memory batch runtime store for the active batch flow.

## Added
- `App\Infrastructure\Batch\Location\InMemoryAddressBatchRuntimeStore`

## Implemented seams
The store implements these App-owned interfaces:
- `AddressBatchJobStoreInterface`
- `AddressBatchJobProgressWriterInterface`
- `AddressBatchResultReaderInterface`
- `AddressBatchResultWriterInterface`

## Why this wave matters
Earlier waves already moved the batch runtime to App-owned message, handler, service, dispatcher, and bus seams.
This wave removes legacy job repository and result storage from the main in-memory runtime/test path.

## Practical effect
A complete App-owned in-memory path now exists:
- `App\Service\Batch\Location\AddressBatchService`
- `App\Infrastructure\Batch\Location\MessageBusAddressBatchMessageDispatcher`
- `App\Infrastructure\Batch\Location\InMemoryAddressBatchMessageBus`
- `App\MessageHandler\Batch\Location\AddressBatchMessageHandler`
- `App\Infrastructure\Batch\Location\InMemoryAddressBatchRuntimeStore`
