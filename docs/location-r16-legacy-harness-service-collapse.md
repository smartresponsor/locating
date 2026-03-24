# Location R16 — legacy harness/service collapse

This wave removes a self-contained legacy harness cluster from the `Smartresponsor\Service\Locator` and `Smartresponsor\ServiceInterface\Locator` surface:

- `ContractTestV2`
- `DryRunSimulator`
- `SandboxHarness`

Rationale:
- these classes were not part of the active Symfony HTTP/config surface,
- no live references remained in active `src/`, `config/`, `composer.json`, `bin/`, or `README.md`,
- their remaining usage was limited to their own legacy tests and historical reports.

Effect:
- the cumulative slice loses another dead `Locator` cluster,
- no active `App\Controller\Http\Location\...` or new `App\Service\...` path depends on these classes.
