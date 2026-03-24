# Locating / Location — current-slice protocol analysis (r01)

## Scope
This analysis is based only on the current slice delivered as `Locating.zip`.
No assumptions were taken from previous archives or earlier repository states.

## Current portrait
The current slice is not yet aligned to the requested Symfony-oriented canon for `Locating/Location`.
The repository is still centered on `Locator` tails and on the `Smartresponsor\` namespace root.

### Primary mismatches
1. **Namespace root mismatch**
   - Required canon: `App\` rooted at `src/`
   - Current slice: `Smartresponsor\`

2. **Entity/workspace tail mismatch**
   - Required canon: `Location`
   - Current slice: `Locator`

3. **Forbidden structural roots currently present**
   - `src/Bundle`
   - `src/Console`
   - `src/Contract`
   - `src/Domain`
   - `src/DomainInterface`
   - `src/Integration`
   - `src/Model`
   - `src/Strategy`

4. **Protocol-noise tokens**
   - `TODO` / `stub` hits are still present in the slice.

## What r01 changes
Wave `r01` does not perform a blind mass rename of hundreds of classes.
Instead, it lays down an executable protocol gate so each next wave can be validated against the canon.

### Added in r01
- `.gate/check/location-protocol-canon.php`
- `.gate/run-location-protocol.sh`
- `report/location-r01-current-slice-manifest.json`
- this analysis document

## Recommended next cumulative waves
### r02 — namespace and composer foundation
- move composer metadata toward `locating/location`
- introduce canonical `App\` namespace root
- prepare controlled migration map from `Smartresponsor\` to `App\`

### r03 — tail canonization
- start targeted migration from `Locator` tails to `Location`
- preserve the only allowed `Location` tail pattern under `src/Entity/Location/...`
- clean tests layout to canonical tails only

### r04 — architectural evacuation
- dissolve `Contract`, `Model`, `Strategy`, `Integration`, `Bundle`, `Console`
- move durable behavior into canonical Symfony layers:
  - `src/Entity`
  - `src/EntityInterface`
  - `src/Service`
  - `src/ServiceInterface`
  - `src/Infrastructure`
  - `src/InfrastructureInterface`
  - `src/Controller`
  - `src/ControllerInterface`
  - `src/Command`
  - `src/CommandInterface`

### r05 — business chain hardening
- verify Doctrine chain
- verify controller/form/twig/bootstrap chain where applicable
- verify CLI chain
- add or strengthen tests around the canonical paths

## Delivery note
From this point, each next cumulative archive should use the immediately previous cumulative snapshot as the active working slice.
