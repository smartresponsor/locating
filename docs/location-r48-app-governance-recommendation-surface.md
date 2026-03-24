# Location R48 — App governance recommendation surface

This wave introduces an App-owned governance recommendation path for providers.

## Added
- provider governance recommendation read-model
- provider governance recommendation service
- governance recommendation controller and route

## Endpoint
- `GET /location/governance/recommendations`

## Purpose
The endpoint complements governance reports, metrics, and explanations with
operator-facing recommendations such as rerouting, lowering provider priority,
and reviewing budget or quota posture.
