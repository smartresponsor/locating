# Bridge Targets — E1-09
## Residual non-adapter blockers
### B-01 `src/Command/Address/LocationNormalizeCommand.php`
- current dependency: `Smartresponsor\Service\Locator\LocatorService`, `Smartresponsor\Model\Locator\CanonicalAddress`
- target action: replace with App-owned normalize service + App-owned canonical DTO/view.
### B-02 `src/Command/Geo/LocationReverseCommand.php`
- current dependency: `Smartresponsor\Service\Locator\LocatorService`, `Smartresponsor\Model\Locator\GeoPoint`
- target action: replace with App-owned reverse service + App-owned geo input DTO.

## Edge adapters still acceptable for now
- `src/Infrastructure/Batch/Location/SmartresponsorAddressBatchJobRecord.php`
- `src/Infrastructure/Batch/Location/SmartresponsorAddressBatchJobRecordFactoryBackend.php`
- `src/Infrastructure/Batch/Location/SmartresponsorAddressBatchJobRepositoryBackend.php`
- `src/Infrastructure/Batch/Location/SmartresponsorAddressBatchLegacyMessageBusBackend.php`
- `src/Infrastructure/Batch/Location/SmartresponsorAddressBatchLegacyResultBackend.php`
- `src/Infrastructure/Batch/Location/SmartresponsorAddressBatchResultRecord.php`
- `src/Infrastructure/Batch/Location/SmartresponsorAddressBatchResultStorageBackend.php`
- `src/Infrastructure/Provider/Location/SmartresponsorAddressReverseHttpBackend.php`
- `src/Infrastructure/Provider/Location/SmartresponsorAddressSuggestBackend.php`
- `src/Infrastructure/Provider/Location/SmartresponsorLocationMetricBackend.php`
- `src/Infrastructure/Provider/Location/SmartresponsorProviderCostCatalogBackend.php`
- `src/Infrastructure/Provider/Location/SmartresponsorProviderHealthSnapshotBackend.php`
- `src/Infrastructure/Provider/Location/SmartresponsorProviderMetricSnapshotBackend.php`
- `src/Infrastructure/Provider/Location/SmartresponsorProviderQuotaDecisionBackend.php`
- `src/Service/Http/Location/SmartresponsorLocationQuotaGuardBackend.php`
