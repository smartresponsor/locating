# Location r31 — legacy provider-router bridge collapse

This wave removes an orphaned legacy provider-router bridge cluster from the Locator surface.

Removed files:
- src/Service/Locator/AddressGeocodeBridge.php
- src/Service/Locator/AddressProviderRouter.php
- src/ServiceInterface/Locator/AddressGeocodeBridgeInterface.php
- src/ServiceInterface/Locator/AddressProviderRouterInterface.php
- tests/Locator/Service/AddressProviderRouterFailoverTest.php

Rationale:
- active App-owned suggest/reverse capability and provider boundaries are already in place
- no active wiring in config/services.php or route surface depended on this cluster
- the remaining test was dedicated to this legacy cluster
