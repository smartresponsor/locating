# Location R34 — legacy HTTP trace/route cluster collapse

This wave removes an orphaned legacy HTTP mini-cluster under `src/Http/Locator`.

Removed:
- `src/Http/Locator/RouteController.php`
- `src/Http/Locator/TraceMiddleware.php`
- `tests/Locator/TraceSpanTest.php`

Rationale:
- no active Symfony route or service wiring referenced these classes
- remaining references were limited to the removed dedicated legacy test
- active runtime remains on the App-owned HTTP and batch paths
