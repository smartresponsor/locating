# Locating LC-36 — Service Locator ProviderStatAggregator legacy path normalization

LC-36 continues the provider-side retirement track for the legacy `Smartresponsor\Service\Locator` service cluster.

## Scope

Touched runtime file:

- `src/Service/Locator/ProviderStatAggregator.php`
- `src/Service/Provider/Location/ProviderStatAggregatorService.php`

Touched canon files:

- `tools/canon/location-service-locator-provider-stat-aggregator-legacy-path-audit.php`
- `.gate/check/location-service-locator-provider-stat-aggregator-legacy-path-canon.php`

## Canonical decision

`ProviderStatAggregator` is provider-side business/service logic. It belongs under:

- `App\Service\Provider\Location`

The legacy bridge contract is preserved:

- `App\ServiceInterface\Provider\Location\Metrics\ProviderStatAggregatorInterface`

The class receives an explicit service-form name:

- `ProviderStatAggregatorService`

## Apply behavior

The apply script overlays the new files and retires only this exact old file with backup:

- `src/Service/Locator/ProviderStatAggregator.php`

No repository-wide delete, full overwrite, or cumulative snapshot application is used.
