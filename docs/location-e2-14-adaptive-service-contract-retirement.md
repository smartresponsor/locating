# E2-14 — Adaptive service contract namespace retirement

Retired a bounded Smartresponsor service-interface cluster for adaptive helper services by moving the compatibility contracts under `App\Bridge\Legacy\Service\Location\...` and rewiring the legacy implementations to those App-owned bridge contracts.
