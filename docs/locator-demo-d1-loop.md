# Locator D1 – Demo loop for continuous discovery

This envelope introduces a long-running demo loop for the Locator component.

New interfaces and classes:

- `src/ServiceInterface/Locator/LocatorDemoLoopInterface.php`
- `src/Service/Locator/LocatorDemoLoop.php`
- `src/CommandInterface/Locator/LocatorDemoLoopCommandInterface.php`
- `src/Command/Locator/LocatorDemoLoopCommand.php`

The demo loop service:

- Reads records from the same NDJSON fixture as F1/F2 (`fixtures/locator-demo.ndjson`).
- For each round, iterates over all records and calls `AddressPipelineInterface::process()`.
- Wraps calls in a `try/catch` block to keep the loop alive while metric decorators still record errors.
- Sleeps between rounds if configured.

Recommended service wiring (YAML):

```yaml
App\ServiceInterface\Locator\LocatorDemoLoopInterface:
    class: App\Service\Locator\LocatorDemoLoop
    arguments:
        $fixtureReader: '@App\InfrastructureInterface\Locator\LocatorFixtureReaderInterface'
        $addressPipeline: '@App\ServiceInterface\Locator\AddressPipelineInterface'
        $defaultFixturePath: '%kernel.project_dir%/fixtures/locator-demo.ndjson'
```

Console command:

- Service: `App\Command\Locator\LocatorDemoLoopCommand`
- Name: `locator:demo:loop`
- Options:
  - `--tenant=...` (default: `tenant-demo`)
  - `--round=10` (number of rounds, at least 1)
  - `--sleep=2` (seconds between rounds)

Usage examples:

```bash
php bin/console locator:demo:loop
php bin/console locator:demo:loop --round=50 --sleep=1
php bin/console locator:demo:loop --tenant=tenant-demo --round=100 --sleep=0
```

Typical use on a demo or staging environment:

- Run the loop command in the background or in a tmux session.
- Watch Locator-related metrics and logs:
  - success/error rate for `address_pipeline`, `address_suggest`, `address_batch_*`
  - latency distributions
- Keep the loop running while performing changes to the Locator configuration,
  external provider settings or network conditions.
