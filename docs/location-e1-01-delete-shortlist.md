# Delete Shortlist — E1-01

## Duplicate shortlist

### DPL-01 Smartresponsor\MessageHandler\Locator\AddressBatchMessageHandler
- file: `src/MessageHandler/Locator/AddressBatchMessageHandler.php`
- classification: duplicate
- App replacement:
  - `App\MessageHandler\Batch\Location\AddressBatchMessageHandler`
- why removable:
  - App-owned equivalent already exists and active wiring should prefer App path.
- references to remove first:
  - verify with app-to-legacy-import-map and config reference report
- paired test removal:
  - matching legacy-only tests under `tests/Locator/...` if present

### DPL-02 Smartresponsor\Message\Locator\AddressBatchMessage
- file: `src/Message/Locator/AddressBatchMessage.php`
- classification: duplicate
- App replacement:
  - `App\Message\Batch\Location\AddressBatchMessage`
- why removable:
  - App-owned equivalent already exists and active wiring should prefer App path.
- references to remove first:
  - verify with app-to-legacy-import-map and config reference report
- paired test removal:
  - matching legacy-only tests under `tests/Locator/...` if present

### DPL-03 Smartresponsor\InfrastructureInterface\Locator\AddressBatchMessageBusInterface
- file: `src/InfrastructureInterface/Locator/AddressBatchMessageBusInterface.php`
- classification: duplicate
- App replacement:
  - `App\InfrastructureInterface\Batch\Location\AddressBatchMessageBusInterface`
- why removable:
  - App-owned equivalent already exists and active wiring should prefer App path.
- references to remove first:
  - verify with app-to-legacy-import-map and config reference report
- paired test removal:
  - matching legacy-only tests under `tests/Locator/...` if present

### DPL-04 Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface
- file: `src/EntityInterface/Locator/AddressBatchJobInterface.php`
- classification: duplicate
- App replacement:
  - `App\EntityInterface\Location\AddressBatchJobInterface`
- why removable:
  - App-owned equivalent already exists and active wiring should prefer App path.
- references to remove first:
  - verify with app-to-legacy-import-map and config reference report
- paired test removal:
  - matching legacy-only tests under `tests/Locator/...` if present

### DPL-05 Smartresponsor\EntityInterface\Locator\AddressInputInterface
- file: `src/EntityInterface/Locator/AddressInputInterface.php`
- classification: duplicate
- App replacement:
  - `App\EntityInterface\Location\AddressInputInterface`
- why removable:
  - App-owned equivalent already exists and active wiring should prefer App path.
- references to remove first:
  - verify with app-to-legacy-import-map and config reference report
- paired test removal:
  - matching legacy-only tests under `tests/Locator/...` if present

### DPL-06 Smartresponsor\Entity\Locator\AddressBatchJob
- file: `src/Entity/Locator/AddressBatchJob.php`
- classification: duplicate
- App replacement:
  - `App\Entity\Location\AddressBatchJob`
- why removable:
  - App-owned equivalent already exists and active wiring should prefer App path.
- references to remove first:
  - verify with app-to-legacy-import-map and config reference report
- paired test removal:
  - matching legacy-only tests under `tests/Locator/...` if present

### DPL-07 Smartresponsor\Entity\Locator\AddressBatchJobStatus
- file: `src/Entity/Locator/AddressBatchJobStatus.php`
- classification: duplicate
- App replacement:
  - `App\Entity\Location\AddressBatchJobStatus`
- why removable:
  - App-owned equivalent already exists and active wiring should prefer App path.
- references to remove first:
  - verify with app-to-legacy-import-map and config reference report
- paired test removal:
  - matching legacy-only tests under `tests/Locator/...` if present

### DPL-08 Smartresponsor\Entity\Locator\AddressInput
- file: `src/Entity/Locator/AddressInput.php`
- classification: duplicate
- App replacement:
  - `App\Entity\Location\AddressInput`
- why removable:
  - App-owned equivalent already exists and active wiring should prefer App path.
- references to remove first:
  - verify with app-to-legacy-import-map and config reference report
- paired test removal:
  - matching legacy-only tests under `tests/Locator/...` if present

## Dead shortlist

### DED-01 Smartresponsor\Bundle\LocatorBundle
- file: `src/Bundle/LocatorBundle.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-02 Smartresponsor\Strategy\Locator\GoogleLocator
- file: `src/Strategy/Locator/GoogleLocator.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-03 Smartresponsor\Strategy\Locator\MapboxLocator
- file: `src/Strategy/Locator/MapboxLocator.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-04 Smartresponsor\Strategy\Locator\OpenStreetMapLocator
- file: `src/Strategy/Locator/OpenStreetMapLocator.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-05 Smartresponsor\Strategy\Locator\USPSLocator
- file: `src/Strategy/Locator/USPSLocator.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-06 Smartresponsor\Domain\Locator\AbTestRouterInterface
- file: `src/ServiceInterface/Locator/AbTestRouterInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-07 Smartresponsor\Domain\Locator\AdaptiveOrderingInterface
- file: `src/ServiceInterface/Locator/AdaptiveOrderingInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-08 Smartresponsor\LayerInterface\Domaine\AdaptiveProviderOrderInterface
- file: `src/ServiceInterface/Locator/AdaptiveProviderOrderInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-09 Smartresponsor\Domain\Locator\AdaptiveQuotaInterface
- file: `src/ServiceInterface/Locator/AdaptiveQuotaInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-10 Smartresponsor\Domain\Locator\AdaptiveTimeoutCalibratorInterface
- file: `src/ServiceInterface/Locator/AdaptiveTimeoutCalibratorInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-11 Smartresponsor\ServiceInterface\Locator\AdaptiveTimeoutInterface
- file: `src/ServiceInterface/Locator/AdaptiveTimeoutInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-12 Smartresponsor\ServiceInterface\Locator\AddressCanonicalizerInterface
- file: `src/ServiceInterface/Locator/AddressCanonicalizerInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-13 Smartresponsor\Domain\Locator\AddressHintBiasInterface
- file: `src/ServiceInterface/Locator/AddressHintBiasInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-14 Smartresponsor\Domain\Locator\AddressInterface
- file: `src/ServiceInterface/Locator/AddressInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-15 Smartresponsor\ServiceInterface\Locator\AddressQuotaGuardInterface
- file: `src/ServiceInterface/Locator/AddressQuotaGuardInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-16 Smartresponsor\ServiceInterface\Locator\AddressReverseInterface
- file: `src/ServiceInterface/Locator/AddressReverseInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-17 Smartresponsor\ServiceInterface\Locator\AddressSuggestInterface
- file: `src/ServiceInterface/Locator/AddressSuggestInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-18 Smartresponsor\ServiceInterface\Locator\AddressSuggestRankerInterface
- file: `src/ServiceInterface/Locator/AddressSuggestRankerInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-19 Smartresponsor\Domain\Locator\AnomalyDetectorInterface
- file: `src/ServiceInterface/Locator/AnomalyDetectorInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-20 Smartresponsor\Domain\Locator\BanditPolicyInterface
- file: `src/ServiceInterface/Locator/BanditPolicyInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-21 Smartresponsor\Domain\Locator\BanditRouterInterface
- file: `src/ServiceInterface/Locator/BanditRouterInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-22 Smartresponsor\Domain\Locator\BatchGeocodeEndpointInterface
- file: `src/ServiceInterface/Locator/BatchGeocodeEndpointInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-23 Smartresponsor\ServiceInterface\Locator\BatchSchedulerInterface
- file: `src/ServiceInterface/Locator/BatchSchedulerInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-24 Smartresponsor\Domain\Locator\BudgetGuardInterface
- file: `src/ServiceInterface/Locator/BudgetGuardInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any

### DED-25 Smartresponsor\Domain\Locator\CanaryGuardInterface
- file: `src/ServiceInterface/Locator/CanaryGuardInterface.php`
- classification: dead
- why removable:
  - No active runtime role detected in this inventory.
- known references:
  - none or legacy-only docs/tests
- paired test removal:
  - matching legacy-only tests if any
