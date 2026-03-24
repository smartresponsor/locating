# Location R44 — App provider governance read-model

This wave introduces an App-owned provider governance read-model for the active observability surface.

## Added
- `ProviderGovernanceSnapshot` App DTO
- `ProviderGovernanceCatalogService` App service
- governance section in `LocationStatusReport`
- provider governance metrics in Prometheus export

## Effect
Status and metrics surfaces now consume a unified App governance catalog built from App signal readers instead of stitching together raw legacy signal shapes inside controllers or export services.
