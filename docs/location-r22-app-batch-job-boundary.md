# Location R22 — App-owned batch job boundary

This wave extracts the batch job read boundary into `App\Entity\Location`.

## Added
- `App\Entity\Location\AddressBatchJob`
- `App\Entity\Location\AddressBatchJobStatus`
- `App\EntityInterface\Location\AddressBatchJobInterface`
- `App\Service\Batch\Location\AddressBatchJobFactory`
- `App\ServiceInterface\Batch\Location\AddressBatchJobFactoryInterface`

## Effect
The App batch service surface no longer returns legacy `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface` directly. Legacy repository output is converted at the App boundary.
