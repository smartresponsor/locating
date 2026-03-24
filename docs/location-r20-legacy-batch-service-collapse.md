# Location r20 — Legacy batch service surface collapse

## Scope

This wave removes the remaining legacy `Locator` batch service surface that became orphaned after the operational switch to the App-owned batch service layer in r19.

## Removed files

- `src/Service/Locator/AddressBatchService.php`
- `src/Service/Locator/AddressBatchServiceMetricDecorator.php`
- `src/ServiceInterface/Locator/AddressBatchServiceInterface.php`
- `src/ServiceInterface/Locator/AddressBatchServiceMetricDecoratorInterface.php`
- `tests/Locator/Service/AddressBatchServiceTest.php`
- `tests/Locator/Service/MetricDecoratorTest.php`

## Why this is safe

- Active Symfony service wiring now points to `App\\Service\\Batch\\Location\\AddressBatchService` and `App\\Service\\Batch\\Location\\AddressBatchServiceMetricDecorator`.
- The legacy batch service classes had no remaining references in `src/`, `config/`, `composer.json`, `bin/`, or `README.md` outside their own self-tests.
- The removed tests exercised only the removed legacy classes and did not cover the active App-owned batch surface.

## Result

The cumulative slice no longer ships a parallel legacy batch service implementation for the same batch orchestration concern. The active service surface is the App-owned `Batch/Location` layer introduced in r19.
