# Location R24 — App batch handler infrastructure boundary

This wave removes direct legacy batch repository/result-storage imports from the active batch message handler.

## Added
- `App\InfrastructureInterface\Batch\Location\AddressBatchJobProgressWriterInterface`
- `App\InfrastructureInterface\Batch\Location\AddressBatchResultWriterInterface`
- `App\Infrastructure\Batch\Location\LegacyAddressBatchJobProgressWriter`
- `App\Infrastructure\Batch\Location\LegacyAddressBatchResultWriter`

## Updated
- `Smartresponsor\MessageHandler\Locator\AddressBatchMessageHandler`
- `config/services.php`

## Effect
The operational handler now depends on App-owned batch write/progress boundaries instead of directly depending on legacy repository/storage contracts.
