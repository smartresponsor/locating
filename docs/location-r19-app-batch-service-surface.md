# Location R19 — App batch service surface

This wave extracts the batch service surface into `App\Service\Batch\Location\...`.

Delivered:
- `App\ServiceInterface\Batch\Location\AddressBatchServiceInterface`
- `App\Service\Batch\Location\AddressBatchService`
- `App\Service\Batch\Location\AddressBatchServiceMetricDecorator`
- Symfony service wiring aliases the App batch interface to the App metric decorator.

Effect:
- batch runtime no longer needs to enter through `Smartresponsor\ServiceInterface\Locator\AddressBatchServiceInterface`
- App-owned batch orchestration now sits alongside the App-owned pipeline introduced earlier.
