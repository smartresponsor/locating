# R42 — Legacy observability helper cluster collapse

This wave removes an orphan legacy observability/helper cluster around trace middleware,
OTel exporter, structured logging, and a standalone Prometheus helper.

Removed surface:
- `src/Service/Locator/TraceMiddleware.php`
- `src/Service/Locator/OtelExporter.php`
- `src/ServiceInterface/Locator/TraceMiddlewareInterface.php`
- `src/ServiceInterface/Locator/OtelExporterInterface.php`
- `src/Infrastructure/Locator/StructuredLogger.php`
- `src/Infrastructure/Locator/TraceMiddleware.php`
- `src/Infrastructure/Locator/OtelExporter.php`
- `src/Infrastructure/Locator/Metrics/Prometheus.php`
- matching infrastructure interfaces
- dedicated legacy tests

Why this is safe:
- active App observability surface now runs through `App\Service\Observability\Location\...`
- `LocationStatusHttpService` and `LocationMetricsHttpService` already depend on App-owned services and read-models
- no live Symfony routing/service wiring points to this removed helper cluster
- remaining matches are historical docs/reports only
