# Location R41 - App-owned observability read-model boundary

Wave 41 extracts the active status and metrics HTTP surface away from direct legacy metric snapshot access.

## Added
- App-owned provider metric snapshot read-model
- App-owned location status report read-model
- App-owned observability services for status and Prometheus metrics export
- App-owned metric snapshot store infrastructure seam

## Shift in runtime
Before:
- StatusController -> legacy MetricSnapshotProviderInterface
- MetricsController -> legacy MetricSnapshotProviderInterface

After:
- StatusController -> App LocationStatusReportServiceInterface -> App ProviderMetricSnapshotStoreInterface -> legacy adapter
- MetricsController -> App LocationMetricsExportServiceInterface -> App ProviderMetricSnapshotStoreInterface -> legacy adapter

## Effect
The public observability surface no longer depends directly on the legacy locator metric snapshot shape.
