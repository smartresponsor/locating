# Locating LC-40 — Service Locator ProviderFailoverRouter legacy path normalization

LC-40 retires the remaining root-level legacy `ProviderRouter` class into the canonical Provider/Location service layer.

## Moved

- `src/Service/Locator/ProviderRouter.php`
- `src/Service/Provider/Location/ProviderFailoverRouterLegacyService.php`

The class is intentionally named `ProviderFailoverRouterLegacyService` to avoid colliding with the already-normalized provider implementation router `ProviderRouterService`.

## Canonical target

- Namespace: `App\Service\Provider\Location`
- Class: `ProviderFailoverRouterLegacyService`
- Contract: `ProviderRouterLegacyInterface`

The old path is retired only by the apply script and only after backup.
