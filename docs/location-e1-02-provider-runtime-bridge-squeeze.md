# Locating / Location — E1-02 Provider Runtime Bridge Squeeze

## Objective
Extract the first live provider/runtime bridge blockers from direct `App -> Smartresponsor` service wiring into App-owned backend contracts.

## Scope
This wave targets the provider/runtime infra edge only:
- suggest backend
- reverse http backend
- metric recorder backend
- health snapshot backend
- metric snapshot backend
- cost catalog backend
- quota decision backend

## What changed
- Added 7 App-owned backend interfaces under `App\InfrastructureInterface\Provider\Location\...BackendInterface`
- Added 7 edge adapters under `App\Infrastructure\Provider\Location\Smartresponsor...Backend`
- Rewired existing App gateways/stores to depend on App backend interfaces instead of direct Smartresponsor contracts
- Rewired `config/services.php` so the first provider/runtime bridge blockers no longer appear as direct Smartresponsor service definitions in this slice of the App graph

## Bridge effect
- direct provider/runtime Smartresponsor refs in the targeted `config/services.php` block: 0
- Smartresponsor refs still present globally in `config/services.php`: 6
- Smartresponsor refs still present under provider runtime infra paths: 7

## Result
This wave does not shut down the global bridge yet.
It narrows the bridge surface by moving the first live provider/runtime seam behind App-owned contracts, which is the intended first Winner Pack A step.
