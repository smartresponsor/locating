Locator R31 – Test audit template

Goal: map real tests to business logic matrix from R30 and highlight gaps.

Sections:

1. Mapping table
   - For each FeatureKey from R30, list:
     - TestSuite (file or namespace)
     - TestType (unit / integration / http / e2e)
     - CoverageNotes (what is actually verified)
     - Gaps (edge cases not covered)

2. Critical business cases
   - List scenarios where failure is unacceptable (for example: address validation in checkout).
   - For each, link to specific tests or mark as GAP.

3. Dirty input and boundaries
   - List tests that use malformed input, rare address formats, large payloads.
   - Add TODO lines where such tests do not exist yet.

4. Summary
   - Top 5 gaps to close before RC1 (short bullet list).
