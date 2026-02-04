# Locator release notes – TEMPLATE

> Copy this file and adjust for each release (RC, GA, patch).

## 1. Overview

- Version: vX.Y.Z
- Type: [RC|GA|Patch]
- Date: YYYY-MM-DD
- Scope summary (one or two sentences).

## 2. Changes

### 2.1 Features

- ...

### 2.2 Fixes

- ...

### 2.3 Internal / tooling

- ...

## 3. SLO and observability

- Target SLOs:
  - suggest/reverse p95 latency: <= 700 ms
  - error rate: <= 0.5 %

- Observability updates:
  - metrics: ...
  - dashboards: ...
  - alerts: ...

- SLO outcome for this release:
  - [ ] SLO passed in staging
  - [ ] Canary window completed without regressions
  - [ ] No high-severity incidents during the window

## 4. Compatibility and upgrades

- Breaking changes: [yes/no]
- Configuration changes:
  - ...

- Data/storage changes (migrations, new indices):
  - ...

- Client impact (SDKs, integrations):
  - ...

## 5. Rollout and rollback

- Recommended rollout strategy:
  - ...

- Rollback procedure:
  - ...

## 6. Known issues

- ...
