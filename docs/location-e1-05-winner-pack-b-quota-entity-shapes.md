# E1-05 / Winner Pack B

## Scope
- move `LocationQuotaGuard` off direct `Smartresponsor` imports via App-owned backend seam
- remove direct legacy `Locator` entity/result typehints from App batch/result factory contracts

## Result
- active HTTP quota guard now depends on `App\ServiceInterface\Http\Location\LocationQuotaGuardBackendInterface`
- batch result storage/job repository App contracts no longer expose `Smartresponsor\Entity*\Locator\...`
- `LocationResultFactoryInterface` no longer typehints legacy result/suggestion entities
