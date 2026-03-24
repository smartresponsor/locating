# Location R39 — App-owned provider signal read-model

This wave extracts an App-owned provider signal boundary for health and quota signals.

## Scope
- Add App DTOs for provider health and quota signals.
- Add App reader interfaces for provider signals.
- Move legacy snapshot/quota-manager shape handling behind App readers.
- Rebind suggest/reverse policy services to App signal readers.

## Result
Policy services now consume App-owned signal DTOs instead of reading raw legacy snapshot arrays or direct quota manager calls.
