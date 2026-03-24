# Bridge Blockers — E1-01

## What still requires `Smartresponsor\ => src/`

### B-01
- App consumer: `App\ServiceInterface\Address\Location\LocationResultFactoryInterface`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-02
- App consumer: `App\ServiceInterface\Address\Location\LocationResultFactoryInterface`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressSuggestionInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-03
- App consumer: `App\ServiceInterface\Bridge\Batch\Location\LegacyAddressResultFactoryInterface`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-04
- App consumer: `App\ServiceInterface\Batch\Location\AddressBatchJobFactoryInterface`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface as LegacyAddressBatchJobInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-05
- App consumer: `App\ServiceInterface\Batch\Location\AddressBatchJobFactoryInterface`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-06
- App consumer: `App\Service\Http\Location\LocationQuotaGuard`
- legacy dependency: `Smartresponsor\Service\Locator\AddressQuotaGuard as InnerAddressQuotaGuard`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-07
- App consumer: `App\Service\Http\Location\LocationQuotaGuard`
- legacy dependency: `Smartresponsor\ServiceInterface\Locator\AddressQuotaGuardInterface as InnerAddressQuotaGuardInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-08
- App consumer: `App\Service\Http\Location\LocationQuotaGuard`
- legacy dependency: `Smartresponsor\Service\Locator\AddressQuotaGuard`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-09
- App consumer: `App\Service\Http\Location\LocationQuotaGuard`
- legacy dependency: `Smartresponsor\ServiceInterface\Locator\AddressQuotaGuardInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-10
- App consumer: `App\Service\Address\Location\LocationResultFactory`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-11
- App consumer: `App\Service\Address\Location\LocationResultFactory`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressSuggestionInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-12
- App consumer: `App\Service\Address\Location\LocationResultFactory`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressValidationIssueInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-13
- App consumer: `App\Service\Provider\Location\LegacyAddressReverseProvider`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressData`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-14
- App consumer: `App\Service\Provider\Location\LegacyAddressReverseProvider`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressResult`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-15
- App consumer: `App\Service\Provider\Location\LegacyAddressReverseProvider`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressStatus`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-16
- App consumer: `App\Service\Provider\Location\LegacyAddressReverseProvider`
- legacy dependency: `Smartresponsor\Entity\Locator\GeoPoint`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-17
- App consumer: `App\Service\Bridge\Batch\Location\LegacyAddressResultFactory`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressData`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-18
- App consumer: `App\Service\Bridge\Batch\Location\LegacyAddressResultFactory`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressResult`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-19
- App consumer: `App\Service\Bridge\Batch\Location\LegacyAddressResultFactory`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressStatus`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-20
- App consumer: `App\Service\Bridge\Batch\Location\LegacyAddressResultFactory`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressValidationIssue`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-21
- App consumer: `App\Service\Bridge\Batch\Location\LegacyAddressResultFactory`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-22
- App consumer: `App\Service\Batch\Location\AddressBatchJobFactory`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface as LegacyAddressBatchJobInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-23
- App consumer: `App\Service\Batch\Location\AddressBatchJobFactory`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-24
- App consumer: `App\Infrastructure\Provider\Location\LegacyAddressSuggestGateway`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressSuggestProviderInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-25
- App consumer: `App\Infrastructure\Provider\Location\LegacyAddressReverseGateway`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\ReverseHttpClientInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-26
- App consumer: `App\Infrastructure\Provider\Location\LegacyLocationMetricRecorder`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\MetricRecorderInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-27
- App consumer: `App\Infrastructure\Provider\Location\LegacyProviderHealthSnapshotStore`
- legacy dependency: `Smartresponsor\ServiceInterface\Locator\HealthMonitorInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-28
- App consumer: `App\Infrastructure\Provider\Location\LegacyProviderQuotaDecisionGateway`
- legacy dependency: `Smartresponsor\Domain\Locator\TenantQuotaManagerInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-29
- App consumer: `App\Infrastructure\Provider\Location\LegacyProviderMetricSnapshotStore`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\MetricSnapshotProviderInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-30
- App consumer: `App\Infrastructure\Provider\Location\LegacyProviderCostCatalogGateway`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\ProviderCostCatalogInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-31
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchJobStore`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressBatchJob as LegacyAddressBatchJob`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-32
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchJobStore`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressBatchJobRepositoryInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-33
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchJobStore`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressBatchJob`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-34
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchResultReader`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-35
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchResultReader`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressBatchResultStorageInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-36
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchJobProgressWriter`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface as LegacyAddressBatchJobInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-37
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchJobProgressWriter`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressBatchJobRepositoryInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-38
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchJobProgressWriter`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-39
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchResultWriter`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressBatchResultStorageInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-40
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchMessageBus`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressBatchMessageBusInterface as LegacyAddressBatchMessageBusInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-41
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchMessageBus`
- legacy dependency: `Smartresponsor\Message\Locator\AddressBatchMessage`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-42
- App consumer: `App\Infrastructure\Batch\Location\LegacyAddressBatchMessageBus`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressBatchMessageBusInterface`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-43
- App consumer: `App\Command\Address\LocationNormalizeCommand`
- legacy dependency: `Smartresponsor\Model\Locator\CanonicalAddress`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-44
- App consumer: `App\Command\Address\LocationNormalizeCommand`
- legacy dependency: `Smartresponsor\Service\Locator\LocatorService`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-45
- App consumer: `App\Command\Geo\LocationReverseCommand`
- legacy dependency: `Smartresponsor\Model\Locator\GeoPoint`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-46
- App consumer: `App\Command\Geo\LocationReverseCommand`
- legacy dependency: `Smartresponsor\Service\Locator\LocatorService`
- layer: runtime
- blocker type: runtime
- why still blocking:
  - Active App class still imports legacy namespace directly.
- migration action:
  - replace with App contract or deeper adapter

### B-47
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\MessageHandler\Locator\AddressBatchMessageHandler`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-48
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressSuggestProviderInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-49
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\ReverseHttpClientInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-50
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\MetricRecorderInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-51
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\ServiceInterface\Locator\HealthMonitorInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-52
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\MetricSnapshotProviderInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-53
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\ProviderCostCatalogInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-54
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\Domain\Locator\TenantQuotaManagerInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-55
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressBatchJobRepositoryInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-56
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressBatchMessageBusInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-57
- App consumer: `config/services.php`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressBatchResultStorageInterface`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove

### B-58
- App consumer: `composer.json`
- legacy dependency: `Smartresponsor\\`
- layer: config
- blocker type: config
- why still blocking:
  - Config/autoload still references legacy namespace.
- migration action:
  - rewire to App contract or remove
