# E2-08 / Helper contract namespace retirement

Retired a bounded helper-contract cluster from `Smartresponsor\...` into `App\Bridge\Legacy\Helper\Location\...`.

Scope:
- Health recorder helper contract
- Record/replay store helper contract
- Address canonicalizer helper contract

This keeps remaining legacy helpers behind App-owned bridge contracts and removes duplicate `Smartresponsor` interface declarations that no longer provide value to the active migration path.
