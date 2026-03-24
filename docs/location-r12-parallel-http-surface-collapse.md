# Location R12 — parallel HTTP surface collapse

This wave removes the legacy `Smartresponsor\Controller\Locator\...` HTTP controller surface from the cumulative slice.

## Removed
- `src/Controller/Locator/AddressSuggestController.php`
- `src/Controller/Locator/AddressReverseController.php`
- `src/Controller/Locator/MetricsController.php`
- `src/Controller/Locator/StatusController.php`
- `config/routes/locator.php`
- legacy controller tests that targeted removed `Smartresponsor\Controller\Locator\...`

## Added
- `tests/Controller/Http/StatusControllerTest.php`
- `tests/Controller/Http/MetricsControllerTest.php`

## Result
There is no longer a duplicate legacy HTTP controller entry surface for the address/status/metrics endpoints in the repository cumulative slice. Active routing remains on `App\Controller\Http\Location\...`.
