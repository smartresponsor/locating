# Winner Shortlist — E1-04 Refresh

## Selection rule
Only legacy artifacts that still block bridge shutdown in active runtime.

### W-01 Smartresponsor\ServiceInterface\Locator\AddressQuotaGuardInterface
- file: `src/ServiceInterface/Locator/AddressQuotaGuardInterface.php`
- current role: legacy quota contract still imported by `App\Service\Http\Location\LocationQuotaGuard`
- used by:
  - `App\Service\Http\Location\LocationQuotaGuard`
- why winner:
  - still sits in active HTTP runtime and blocks bridge shutdown
- target App home:
  - `App\ServiceInterface\Http\Location\LocationQuotaGuardBackendInterface`
- migration action:
  - rehome behind App backend seam
- done criteria:
  - `LocationQuotaGuard` no longer imports `Smartresponsor\ServiceInterface\Locator\AddressQuotaGuardInterface`

### W-02 Smartresponsor locator entities used by batch/result factories
- file: `src/Entity/Locator/*` and `src/EntityInterface/Locator/*` selected by batch/result factories
- current role: legacy entity/result shapes still leak into App bridge contracts
- used by:
  - `App\Service\Address\Location\LocationResultFactory`
  - `App\Service\Bridge\Batch\Location\LegacyAddressResultFactory`
  - `App\Service\Batch\Location\AddressBatchJobFactory`
- why winner:
  - these shapes still block full App-owned batch/result contract completion
- target App home:
  - `App\Entity\Location\...` equivalents only
- migration action:
  - replace legacy entity/result references with App DTO/read-model contracts
- done criteria:
  - App factories and backend contracts no longer typehint `Smartresponsor\Entity*\Locator\...`
