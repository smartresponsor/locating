# Locating LC-39 — Service Locator ProviderCostCatalog Legacy Path Normalization

LC-39 continues the provider-side retirement track for the legacy `Smartresponsor\Service\Locator` service cluster.

## Scope

Touched runtime class:

- `src/Service/Locator/ProviderCostCatalog.php`
- `src/Service/Provider/Location/ProviderCostCatalogService.php`

The old path is retired only by the apply script and only after backup.

## Canonical direction

The class now belongs to the Symfony-oriented provider service layer:

- namespace: `App\Service\Provider\Location`
- class: `ProviderCostCatalogService`
- bridge contract: `App\InfrastructureInterface\Provider\Location\Provider\ProviderCostCatalogInterface`

## Out of scope

LC-39 does not rewrite provider catalog persistence or replace the legacy in-memory behavior. It only aligns the physical path, namespace, and class-form suffix so later waves can reduce the remaining `Smartresponsor\Service\Locator` surface incrementally.
