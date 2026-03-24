# E1-08 / Dead Purge A — Integration Selector Cluster

Removed an orphan legacy integration/selector cluster that still referenced strategy classes already purged in E1-07.

Removed files:
- src/Integration/Locator/Provider/GoogleProvider.php
- src/Integration/Locator/Provider/MapboxProvider.php
- src/Integration/Locator/Provider/OpenStreetMapProvider.php
- src/Integration/Locator/LocatorSelector.php
- src/Integration/Locator/Health/HealthChecker.php
- src/Infrastructure/Locator/LocatorSelector.php
- src/InfrastructureInterface/Locator/LocatorSelectorInterface.php

Rationale:
- no active App runtime wiring
- no config/routes references
- internal-only references inside the removed cluster
- directly dependent on strategy surface already removed in E1-07
