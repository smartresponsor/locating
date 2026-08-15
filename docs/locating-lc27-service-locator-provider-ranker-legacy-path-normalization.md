# Locating LC-27 — Service Locator Provider ranker legacy path normalization

LC-27 starts the Provider-side legacy-retirement track after the Address cluster was reduced through LC-17–LC-26.

## Canonical move

- `src/Service/Locator/Provider/Ranker.php`
  → `src/Service/Provider/Location/ProviderRankerService.php`
- `src/ServiceInterface/Locator/RankerInterface.php`
  → `src/ServiceInterface/Provider/Location/ProviderRankerServiceInterface.php`

`ProviderRankerService` keeps the original static `sort(array $items): array` contract because the legacy provider router calls the ranker statically.

## Consumer alignment

`src/Service/Locator/Provider/ProviderRouter.php` is patched to call `ProviderRankerService::sort($res)` instead of relying on the retired same-namespace `Ranker` class.

## Safety

The apply script retires only the two exact legacy paths listed above and stores backups under `.patch-backup/locating-lc27/` before deletion.
