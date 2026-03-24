# Location R13 — Legacy controller interface surface collapse

This wave removes the orphaned legacy `Locator` controller interface surface after the active HTTP entrypoints were already moved to `App\Controller\Http\Location\...` in R11 and the legacy controller implementations were removed in R12.

## Removed

- `src/ControllerInterface/Locator/AddressSuggestControllerInterface.php`
- `src/ControllerInterface/Locator/AddressReverseControllerInterface.php`
- `src/ControllerInterface/Locator/MetricsControllerInterface.php`
- `src/ControllerInterface/Locator/StatusControllerInterface.php`

## Validation notes

Searches across `src/`, `config/`, and `tests/` showed no live references to these legacy interfaces before removal.

## Effect

The cumulative slice no longer keeps a parallel legacy controller contract surface for the already-collapsed `Locator` HTTP layer.
