# Location R23 — App batch infrastructure boundary

This wave extracts the batch infrastructure seam behind App-owned contracts so that `App\Service\Batch\Location\AddressBatchService` no longer imports legacy Smartresponsor batch repository, message bus, or result storage interfaces directly.

## Added App-owned infrastructure contracts
- `App\InfrastructureInterface\Batch\Location\AddressBatchJobStoreInterface`
- `App\InfrastructureInterface\Batch\Location\AddressBatchMessageDispatcherInterface`
- `App\InfrastructureInterface\Batch\Location\AddressBatchResultReaderInterface`

## Added transition adapters
- `App\Infrastructure\Batch\Location\LegacyAddressBatchJobStore`
- `App\Infrastructure\Batch\Location\LegacyAddressBatchMessageDispatcher`
- `App\Infrastructure\Batch\Location\LegacyAddressBatchResultReader`

## Updated operational surface
- `App\Service\Batch\Location\AddressBatchService`
- `config/services.php`

## Result
The App batch service now depends only on App infrastructure contracts. Legacy Smartresponsor infrastructure remains encapsulated inside dedicated adapters.
