# Locator golden fixture suite (R22)

R22 introduces a small "golden" fixture set and a runner script to exercise
the main Locator endpoints in a repeatable way.

## Files

- `tests/Locator/Fixture/address-golden.ndjson`
- `tests/Locator/Fixture/AddressFixtureFileTest.php`
- `tools/locator-fixtures-run.php`

### address-golden.ndjson

This NDJSON file contains a list of human-readable records, for example:

- US: `1600 Pennsylvania Ave NW Washington`
- GB: `10 Downing Street London`
- UA: `Khreshchatyk 1 Kyiv`
- reverse geocoding examples for Houston and Kyiv
- an intentionally invalid coordinate pair

Each line is a JSON document with fields:

- `id` – string identifier
- `kind` – `suggest`, `reverse`, `reverse_invalid` (or future types)
- `input` – operation-specific input payload
- `note` – optional free-form comment

### AddressFixtureFileTest

`App\Tests\Locator\Fixture\AddressFixtureFileTest` ensures that the
fixture file:

- exists,
- is readable,
- contains well-formed JSON with required fields.

This prevents silent drift or accidental deletion of the fixture file.

### locator-fixtures-run.php

This CLI tool reads `address-golden.ndjson` and sends HTTP requests to a
running Locator instance.

Environment:

- `LOCATOR_BASE_URL` – base URL, default `http://localhost:8000`
- `LOCATOR_TENANT` – tenant id, default `demo`

Example:

```bash
php tools/locator-fixtures-run.php
```

For each record it prints:

- id, kind and resolved URL,
- HTTP status line and latency,
- a short summary of response (address, items count, first label, quota flag).

The runner is intentionally lightweight and does not enforce strict
expectations yet – it is meant as a manual and exploratory regression tool
that can later be extended with assertions or CI gates if needed.
