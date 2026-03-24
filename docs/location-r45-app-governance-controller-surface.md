# Location R45 — App governance controller surface

This wave introduces an App-owned governance report/controller surface for the active provider governance read-model.

## Added
- App governance report DTO and interface
- App governance report service
- App governance controller and route `/location/governance`

## Effect
Governance observability is no longer reachable only as a subsection of the generic status report. It now has its own App-owned HTTP/report surface.
