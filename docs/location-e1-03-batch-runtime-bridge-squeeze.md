# Locating / Location — E1-03 Batch Runtime Bridge Squeeze

## Objective
Squeeze remaining batch/runtime bridge blockers by introducing App-owned batch backend interfaces for repository, result storage, and legacy bus edges.

## What changed
- Added App-owned batch backend interfaces for repository, result storage, and legacy bus dispatch.
- Added Smartresponsor-backed implementations of those interfaces.
- Rewired existing batch stores/writers/bus wrappers to depend on App-owned backends instead of direct Smartresponsor infrastructure contracts.
- Reduced direct Smartresponsor references in active batch runtime wiring to the new edge adapters only.

## Expected effect
- Smaller bridge blocker surface in `config/services.php`.
- Active batch App runtime now depends on App-owned backend seams for the remaining Smartresponsor integration edge.
