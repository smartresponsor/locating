# Location R8 – Discovery Loop and Demo Seed

Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp

This envelope hardens the Location discovery loop and demo seed so that a developer can try the component without reading internal implementation details.

## Scope

- Verify that `fixtures/locator-demo.ndjson` can be used as a demo dataset.
- Provide a demo seed service that processes the fixture through `AddressPipeline`.
- Provide a demo loop service that continuously processes fixture records in rounds.
- Add tests that exercise both services and protect the contract.
- Fix namespace issues in `AddressBatchService`.

## Key pieces

- `src/Service/Locator/LocatorDemoSeed.php` with interface `src/ServiceInterface/Locator/LocatorDemoSeedInterface.php`.
- `src/Service/Locator/LocatorDemoLoop.php` with interface `src/ServiceInterface/Locator/LocatorDemoLoopInterface.php`.
- `src/Infrastructure/Locator/LocatorFixtureReader.php` with interface `src/InfrastructureInterface/Locator/LocatorFixtureReaderInterface.php`.
- `fixtures/locator-demo.ndjson` – NDJSON sample addresses for demo.

## How to run the demo (CLI)

Assuming a standard Symfony skeleton with `bin/console` wired and Location services registered:

```bash
# Seed demo records for tenant "tenant-demo"
php bin/console locator:demo:seed --tenant=tenant-demo --file=fixtures/locator-demo.ndjson

# Run a short discovery loop over the same data
php bin/console locator:demo:loop --tenant=tenant-demo --round=3 --sleep=0
```

Both commands are idempotent with respect to the sample file; they only read from `fixtures/locator-demo.ndjson` and rely on `AddressPipeline` for parsing and validation.

## Tests

- `tests/Locator/LocatorDemoSeedTest.php`
  - Uses real `LocatorFixtureReader` and real `AddressPipeline`.
  - Asserts that `seedDemo()` processes at least one record from `fixtures/locator-demo.ndjson`.

- `tests/Locator/LocatorDemoLoopTest.php`
  - Uses a stub fixture reader and a counting pipeline to validate that the loop calls the pipeline once per record per round.

These tests do not depend on external APIs and can be executed in any environment with PHPUnit configured.

## Internal change

- `src/Service/Locator/AddressBatchService.php`
  - Fixed import namespaces from `App.Entity\` / `App.InfrastructureInterface\` style to canonical `App\Entity\` / `App\InfrastructureInterface\`.
  - No behavior change is intended; this is a correctness and tooling fix so static analysis and IDE navigation work as expected.
