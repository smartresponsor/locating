# Delete Shortlist — E1-04 Refresh

## Duplicate shortlist

### DPL-01 `src/Bundle/Locator/SmartResponsorLocatorBundle.php`
- classification: duplicate
- App replacement:
  - no active App-owned runtime requires a legacy locator bundle
- why removable:
  - no active Symfony route/service path in current slice points to this bundle entry
- references to remove first:
  - verify composer/bundle registration before purge
- paired test removal:
  - none detected

### DPL-02 `src/Bundle/Locator/DependencyInjection/SmartResponsorLocatorExtension.php`
- classification: duplicate
- App replacement:
  - `config/services.php` current explicit wiring
- why removable:
  - explicit service wiring already drives active runtime
- references to remove first:
  - verify no bundle bootstrap references remain
- paired test removal:
  - none detected

## Dead shortlist

### DED-01 `src/Strategy/Locator/GoogleLocator.php`
- classification: dead
- why removable:
  - no active App runtime or current config wiring points to legacy strategy class
- known references:
  - none found in current refresh scan
- paired test removal:
  - none

### DED-02 `src/Strategy/Locator/MapboxLocator.php`
- classification: dead
- why removable:
  - no active App runtime or current config wiring points to legacy strategy class
- known references:
  - none found in current refresh scan
- paired test removal:
  - none

### DED-03 `src/Strategy/Locator/OpenStreetMapLocator.php`
- classification: dead
- why removable:
  - no active App runtime or current config wiring points to legacy strategy class
- known references:
  - none found in current refresh scan
- paired test removal:
  - none

### DED-04 `src/Strategy/Locator/USPSLocator.php`
- classification: dead
- why removable:
  - no active App runtime or current config wiring points to legacy strategy class
- known references:
  - none found in current refresh scan
- paired test removal:
  - none
