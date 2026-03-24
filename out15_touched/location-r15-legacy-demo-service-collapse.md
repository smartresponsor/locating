# Locating r15 — legacy demo service collapse

This wave removes the remaining dead demo service surface that became orphaned after the legacy command collapse in r14.

Removed:
- `src/Service/Locator/LocatorDemoLoop.php`
- `src/Service/Locator/LocatorDemoSeed.php`
- `src/ServiceInterface/Locator/LocatorDemoLoopInterface.php`
- `src/ServiceInterface/Locator/LocatorDemoSeedInterface.php`
- `tests/Locator/LocatorDemoLoopTest.php`
- `tests/Locator/LocatorDemoSeedTest.php`

Rationale:
- the demo commands were already removed in r14;
- there are no active code/config/test/runtime references left outside the removed demo test files themselves;
- keeping the demo service surface would preserve dead legacy `Locator` tails without operational value.
