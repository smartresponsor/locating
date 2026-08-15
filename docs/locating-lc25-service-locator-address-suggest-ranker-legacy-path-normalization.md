# Locating LC-25 — Service Locator AddressSuggestRanker legacy path normalization

LC-25 retires the generic legacy locator suggest ranker from Smartresponsor\\Service\\Locator into App\\Service\\Address\\Location.

## Scope

- src/Service/Address/Location/AddressSuggestRankerLegacyService.php
- src/ServiceInterface/Address/Location/AddressSuggestRankerLegacyServiceInterface.php

Retired legacy paths, with backup in the apply script:

- src/Service/Locator/SuggestRanker.php
- src/ServiceInterface/Locator/SuggestRankerInterface.php

## Canonical target

The class is intentionally named AddressSuggestRankerLegacyService to avoid collision with the already-normalized AddressSuggestRanker service while preserving legacy behavior for later review.

## Validation

Run composer dump-autoload, composer lint, composer canon:service-locator-address-suggest-ranker-legacy-path, composer canon:service-locator-address-references, and composer canon:all.
