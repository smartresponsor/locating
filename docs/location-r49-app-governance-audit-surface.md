# Location R49 — App governance audit surface

This wave introduces an App-owned governance audit surface for provider decisions.

Added:
- ProviderGovernanceAuditEntry / ProviderGovernanceAuditReport
- ProviderGovernanceAuditService
- LocationGovernanceAuditHttpService
- `/location/governance/audit`

The audit surface combines explanations and recommendations into a decision log with an explicit operator-facing decision field.
