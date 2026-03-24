# Bridge Blockers — E1-04 Refresh

## What still requires `Smartresponsor\ => src/`

### B-01
- App consumer: `App\ServiceInterface\Address\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-02
- App consumer: `App\ServiceInterface\Address\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressSuggestionInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-03
- App consumer: `App\ServiceInterface\Bridge\Batch\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-04
- App consumer: `App\ServiceInterface\Batch\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface as LegacyAddressBatchJobInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-05
- App consumer: `App\Service\Http\Location`
- legacy dependency: `Smartresponsor\Service\Locator\AddressQuotaGuard as InnerAddressQuotaGuard`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-06
- App consumer: `App\Service\Http\Location`
- legacy dependency: `Smartresponsor\ServiceInterface\Locator\AddressQuotaGuardInterface as InnerAddressQuotaGuardInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-07
- App consumer: `App\Service\Address\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-08
- App consumer: `App\Service\Address\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressSuggestionInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-09
- App consumer: `App\Service\Address\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressValidationIssueInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-10
- App consumer: `App\Service\Provider\Location`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressData`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-11
- App consumer: `App\Service\Provider\Location`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressResult`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-12
- App consumer: `App\Service\Provider\Location`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressStatus`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-13
- App consumer: `App\Service\Provider\Location`
- legacy dependency: `Smartresponsor\Entity\Locator\GeoPoint`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-14
- App consumer: `App\Service\Bridge\Batch\Location`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressData`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-15
- App consumer: `App\Service\Bridge\Batch\Location`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressResult`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-16
- App consumer: `App\Service\Bridge\Batch\Location`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressStatus`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-17
- App consumer: `App\Service\Bridge\Batch\Location`
- legacy dependency: `Smartresponsor\Entity\Locator\AddressValidationIssue`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-18
- App consumer: `App\Service\Bridge\Batch\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-19
- App consumer: `App\Service\Batch\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface as LegacyAddressBatchJobInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-20
- App consumer: `App\InfrastructureInterface\Batch\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface as LegacyAddressBatchJobInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-21
- App consumer: `App\InfrastructureInterface\Batch\Location`
- legacy dependency: `Smartresponsor\EntityInterface\Locator\AddressResultInterface`
- layer: service
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-22
- App consumer: `App\Infrastructure\Provider\Location`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\AddressSuggestProviderInterface`
- layer: infrastructure
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-23
- App consumer: `App\Infrastructure\Provider\Location`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\ReverseHttpClientInterface`
- layer: infrastructure
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-24
- App consumer: `App\Infrastructure\Provider\Location`
- legacy dependency: `Smartresponsor\InfrastructureInterface\Locator\MetricRecorderInterface`
- layer: infrastructure
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

### B-25
- App consumer: `App\Infrastructure\Provider\Location`
- legacy dependency: `Smartresponsor\ServiceInterface\Locator\HealthMonitorInterface`
- layer: infrastructure
- blocker type: runtime
- why still blocking:
  - App class still imports legacy namespace directly
- migration action:
  - replace with App contract or rehome

