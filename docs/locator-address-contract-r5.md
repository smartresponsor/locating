# Locator / AddressResult contract (R5)

This document freezes the external contract for the address result model that is produced
by the address pipeline and consumed by higher layers such as HTTP controllers, message
handlers, or batch processors.

The canonical model is defined by:

- `App\Entity\Locator\AddressStatus` (enum)
- `App\Entity\Locator\AddressValidationIssue`
- `App\Entity\Locator\AddressData`
- `App\Entity\Locator\GeoPoint`
- `App\Entity\Locator\AddressResult`
- `App\EntityInterface\Locator\AddressResultInterface`

## AddressStatus

Enum values:

- `verified`  — address is valid and fully usable.
- `partial`   — address is usable but has one or more non-fatal issues.
- `rejected`  — address is invalid and must not be used as-is.
- `ambiguous` — address might be valid, but there are multiple possible matches.

## AddressResult::toArray() payload

The `toArray()` method of `AddressResult` returns a canonical array with the following
shape:

```php
[
    'status' => string,                // one of: verified, partial, rejected, ambiguous
    'address' => ?array{               // null when there is no usable address
        'street' => string,
        'city' => string,
        'region' => string,
        'postalCode' => string,
        'countryCode' => string,
    },
    'issues' => list<array{
        'field' => string,
        'code' => string,
        'message' => string,
    }>,
    'geoPoint' => null|array{
        'latitude' => float,
        'longitude' => float,
    },
    'providerKey' => ?string,
];
```

Semantics:

- `status` reflects the validation outcome and is the primary flag used by callers.
- `address` contains the normalized address components; the same structure is used inside
  `AddressData::toArray()`.
- `issues` is a list of validation issues; each issue is described by a `field`, a
  machine-readable `code`, and a human-readable `message`.
- `geoPoint` contains coordinates of the resolved address when geocoding is available;
  otherwise it is `null`.
- `providerKey` identifies the provider or strategy which produced the result (for example,
  `osm`, `google`, `usps`).

## Contract tests and fixtures

The file `tests/Locator/Address/AddressContractTest.php` consumes fixtures from
`tests/fixtures/address/address-contract-samples.ndjson` and verifies that:

- every sample can be represented as an `AddressResult` instance;
- the `toArray()` output is stable and matches the fixture content;
- statuses, issues, and geo points behave consistently across all samples.

The fixtures file is the single source of truth for sample addresses used in contract
tests. New samples can be added by appending JSON lines without changing the test code.
