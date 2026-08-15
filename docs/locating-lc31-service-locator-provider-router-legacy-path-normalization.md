# Locating LC-31 — Service Locator ProviderRouter legacy path normalization

LC-31 closes the small Provider-side runtime router slice that was left after moving ProviderRanker, Here, Mapbox, and Nominatim provider implementations.

## Scope

- Moves `src/Service/Locator/Provider/ProviderRouter.php` to `src/Service/Provider/Location/ProviderRouterService.php`.
- Changes namespace from `Smartresponsor\Service\Locator\Provider` to `App\Service\Provider\Location`.
- Renames the class from `ProviderRouter` to `ProviderRouterService`.
- Updates `src/Infrastructure/Locator/Http/Kernel.php` to instantiate the canonical router service.
- Retires only the exact old router file through the apply script with backup.

## Out of scope

- No full Provider subsystem rewrite.
- No destructive repository overwrite.
- No behavior rewrite for hedging, cache, circuit breaker, or provider budgets.
