# Location R50 — App governance remediation plan surface

Wave 50 introduces an App-owned remediation-plan surface for provider governance.

## Added
- remediation step DTO and interfaces
- remediation plan/report DTO and interfaces
- remediation plan service built from App governance audit output
- remediation plan controller and route `/location/governance/remediation-plans`
- Symfony service wiring for the new remediation plan surface

## Effect
Governance now exposes a structured action plan path in addition to report, metrics, explanations, recommendations, and audit.
