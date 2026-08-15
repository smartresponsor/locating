# Locating R11 — config and wiring surface

This wave connects the already introduced `App\Service\Http\Location\...` and `App\Service\...` verticals to Symfony routing and container wiring.

## Delivered
- `config/services.php` now declares canonical App-owned aliases and explicit service registrations for the new Location HTTP, capability, provider, and infrastructure seams.
- `config/routes/*` now point to `App\Service\Http\Location\...` controllers.
- route paths were normalized from `/locator/...` to `/location/...` on the new vertical.
- config-focused tests were added to pin the routing and service-wiring surface.

## Important note
This wave intentionally leaves the large legacy `Smartresponsor\...` graph in place, but moves the Symfony entrypoints onto the new App-owned Location vertical.
