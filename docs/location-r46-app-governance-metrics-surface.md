# Location R46 — App governance metrics surface

This wave extends the App-owned governance contour with a dedicated Prometheus-style export path.

Added:
- App governance metrics read-model entity
- App governance metrics export service
- App governance metrics controller and route
- Explicit service wiring for the new export surface

Result:
- governance reporting now has a dedicated text/plain metrics endpoint
- governance metrics are no longer piggybacked only through the general metrics exporter
