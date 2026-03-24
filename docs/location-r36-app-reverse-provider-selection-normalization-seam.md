# Location R36 — App reverse provider selection / normalization seam

This wave deepens the active reverse contour.

## What changed
- Added App-owned reverse source boundary.
- Added App-owned reverse source ordering seam.
- Added App-owned reverse result normalizer seam.
- Added OrderedAddressReverseProvider as the active App reverse orchestration layer.
- Demoted LegacyAddressReverseProvider to a reverse source adapter.
- Fixed a real wiring bug in config/services.php where LegacyAddressReverseProvider arguments were previously ordered incorrectly.

## Runtime effect
Active reverse path is now:

AddressReverseCapability -> OrderedAddressReverseProvider -> ordered App reverse sources -> App reverse result normalizer -> App reverse result

Legacy reverse gateway remains only at the infrastructure edge.
