# E1-07 / Duplicate Purge A

This wave removes the first validated legacy duplicate/dead shortlist from the E1 refresh.

Removed clusters:
- legacy bundle/bootstrap surface for Locator
- legacy provider strategy classes: Google, Mapbox, OpenStreetMap, USPS
- legacy test that validated only the removed strategy surface

Rationale:
- these files were validated as duplicate/dead in E1 refresh
- active App runtime no longer depends on this bundle/bootstrap path
- provider/runtime orchestration has already moved to App-owned seams
