# Locating LC-18 — Service Locator Address ranker path normalization

LC-18 is the second small real normalization wave inside the legacy `Smartresponsor\Service\Locator` Address cluster.

## Scope

Moved exactly two files:

- `src/Service/Locator/AddressSuggestRanker.php`
- `src/ServiceInterface/Locator/AddressSuggestRankerInterface.php`

Canonical targets:

- `src/Service/Address/Location/AddressSuggestRanker.php`
- `src/ServiceInterface/Address/Location/AddressSuggestRankerInterface.php`

## Rules

- No repository-wide cleanup.
- No cumulative overwrite.
- Retire only the two touched legacy paths with backup.
- Keep the class name because `Ranker` is already an accepted service-form suffix.
- Move from legacy `Smartresponsor\...` to Symfony-oriented `App\Service\Address\Location` / `App\ServiceInterface\Address\Location`.

## Validation

Run:

```bash
composer lint
composer canon:service-locator-address-ranker-path
composer canon:all
```
