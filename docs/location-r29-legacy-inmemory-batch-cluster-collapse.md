# Location R29 — Legacy in-memory batch cluster collapse

## Scope
This wave removes the remaining legacy in-memory batch repository/result-storage cluster that was only retained for legacy adapter tests.

## Removed legacy cluster
- `Smartresponsor\Infrastructure\Locator\InMemoryAddressBatchJobRepository`
- `Smartresponsor\Infrastructure\Locator\InMemoryAddressBatchMessageBus`
- `Smartresponsor\Infrastructure\Locator\InMemoryAddressBatchResultStorage`
- matching `InMemory*Interface` marker interfaces
- legacy-adapter tests that depended on these in-memory concretes

## Why this is safe
The active App-owned batch runtime path already exists through:
- `App\Infrastructure\Batch\Location\InMemoryAddressBatchRuntimeStore`
- `App\Infrastructure\Batch\Location\InMemoryAddressBatchMessageBus`
- `App\MessageHandler\Batch\Location\AddressBatchMessageHandler`
- `App\Service\Batch\Location\AddressBatchService`

Search across `src/`, `config/`, `composer.json`, `README.md`, and surviving `tests/` showed no production/runtime references to the removed legacy in-memory classes.

## Result
The cumulative slice now keeps App-owned in-memory batch runtime facilities as the canonical runtime/test path and drops the parallel legacy in-memory batch harness.
