# Location R14 — legacy command surface collapse

This wave removes the orphaned legacy `Locator` command surface that remained after the active Symfony wiring had already shifted to the new `App`-owned `Location` HTTP layer.

## Removed

- `src/Command/Locator/LocatorDemoLoopCommand.php`
- `src/Command/Locator/LocatorDemoSeedCommand.php`
- `src/CommandInterface/Locator/LocatorDemoLoopCommandInterface.php`
- `src/CommandInterface/Locator/LocatorDemoSeedCommandInterface.php`

## Why removal was safe

A repository-wide reference check across `src/`, `config/`, `tests/`, `bin/`, `composer.json`, and `README.md` showed no live usages beyond the files being removed themselves.

The removed command classes were not wired in `config/services.php`, were not referenced by active route/config surfaces, and had no surviving test coverage that exercised them as current operational entrypoints.

## Remaining follow-up

The legacy demo service layer under `src/Service/Locator/...` and `src/ServiceInterface/Locator/...` still exists and should be evaluated in later waves. This wave only collapses the dead command and command-interface surface.
