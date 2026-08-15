# Location R33 — Legacy batch controller cluster collapse

This wave removes an orphaned legacy batch controller cluster that was no longer part of the active App-owned batch runtime.

Removed cluster:
- `src/Http/Locator/BatchService.php`
- `src/Service/Locator/BatchService.php`
- `src/ServiceInterface/Locator/BatchServiceInterface.php`
- `src/Entity/Locator/BatchService.php`
- `tests/Locator/BatchServiceTest.php`

Why this was safe:
- no active Symfony routing pointed to `Smartresponsor\Http\Locator\BatchService`
- no current App batch wiring depended on `Smartresponsor\Service\Locator\BatchService`
- references were limited to the removed controller test and historical reports

Effect:
- the cumulative slice keeps the App-owned batch service/message/handler path as the single active batch contour
- one more dead `Locator` HTTP/service/entity cluster is removed from the repository
