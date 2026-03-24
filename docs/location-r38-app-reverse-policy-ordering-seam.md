# Location r38 — App reverse policy ordering seam

This wave deepens the live reverse contour by replacing static reverse source ordering with an App-owned policy-driven seam.

## Added
- AddressReverseSourceHealthPolicyInterface
- AddressReverseSourceQuotaPolicyInterface
- LegacyHealthAwareAddressReverseSourceHealthPolicy
- LegacyQuotaAwareAddressReverseSourceQuotaPolicy
- PolicyAddressReverseSourceOrder

## Changed
- AddressReverseSourceInterface now exposes sourceKey().
- LegacyAddressReverseProvider now acts as a named reverse source.
- config/services.php now wires reverse ordering through policy-based health/quota-aware ordering.

## Result
Active reverse runtime keeps legacy gateway access only at the edge, while orchestration and source ordering remain App-owned.
