# Location R51 — App governance execution and acknowledgement surface

This wave introduces an App-owned execution and acknowledgement surface for provider governance remediation.

## Added
- execution step status read-model
- execution report read-model
- execution service
- governance execution controller and route

## Operational effect
The governance contour now exposes a dedicated execution path at `/location/governance/execution` so remediation plans can be viewed together with acknowledgement state and per-step execution readiness.
