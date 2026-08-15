# Location R12 — parallel HTTP surface collapse

This wave removes the legacy `Smartresponsor\Service\Locator\...` HTTP controller surface from the cumulative slice.

## Removed
- `src/Service/Locator/LocationAddressSuggestHttpService.php`
- `src/Service/Locator/LocationAddressReverseHttpService.php`
- `src/Service/Locator/LocationMetricsHttpService.php`
- `src/Service/Locator/LocationStatusHttpService.php`
- `config/routes/locator.php`
- legacy controller tests that targeted removed `Smartresponsor\Service\Locator\...`

## Added
- `tests/Service/Http/LocationStatusHttpServiceTest.php`
- `tests/Service/Http/LocationMetricsHttpServiceTest.php`

## Result
There is no longer a duplicate legacy HTTP controller entry surface for the address/status/metrics endpoints in the repository cumulative slice. Active routing remains on `App\Service\Http\Location\...`.
