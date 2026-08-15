# Location R47 — App governance explanation surface

This wave introduces an App-owned governance explanation path for provider policy decisions.

## Added
- `ProviderGovernanceExplanation` and `ProviderGovernanceExplanationReport`
- `ProviderGovernanceExplanationService`
- `LocationGovernanceExplanationHttpService`
- route `/location/governance/explanations`

## Purpose
Expose human-readable governance reasons for provider degradation, quota denial, latency pressure, and cost pressure without coupling controllers to legacy signal shape.
