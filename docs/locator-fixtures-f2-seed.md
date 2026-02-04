# Locator F2 – Demo seed service and console command

This envelope introduces a reusable demo seed for the Locator pipeline.

New interfaces and classes:

- `src/InfrastructureInterface/Locator/LocatorFixtureReaderInterface.php`
- `src/Infrastructure/Locator/LocatorFixtureReader.php`
- `src/ServiceInterface/Locator/LocatorDemoSeedInterface.php`
- `src/Service/Locator/LocatorDemoSeed.php`
- `src/CommandInterface/Locator/LocatorDemoSeedCommandInterface.php`
- `src/Command/Locator/LocatorDemoSeedCommand.php`

Recommended service wiring (Symfony):

- `LocatorFixtureReaderInterface` → `LocatorFixtureReader`
- `LocatorDemoSeedInterface` → `LocatorDemoSeed`
- The `LocatorDemoSeed` service must receive `defaultFixturePath` pointing to `fixtures/locator-demo.ndjson`.

Example service configuration fragment (YAML):

```yaml
App\InfrastructureInterface\Locator\LocatorFixtureReaderInterface:
    class: App\Infrastructure\Locator\LocatorFixtureReader

App\ServiceInterface\Locator\LocatorDemoSeedInterface:
    class: App\Service\Locator\LocatorDemoSeed
    arguments:
        $fixtureReader: '@App\InfrastructureInterface\Locator\LocatorFixtureReaderInterface'
        $addressPipeline: '@App\ServiceInterface\Locator\AddressPipelineInterface'
        $defaultFixturePath: '%kernel.project_dir%/fixtures/locator-demo.ndjson'
```

Console command:

- Service: `App\Command\Locator\LocatorDemoSeedCommand`
- Name: `locator:demo:seed`
- Options:
  - `--tenant=...` (default: `tenant-demo`)
  - `--file=...` (optional override for fixture path)

Usage examples:

```bash
php bin/console locator:demo:seed
php bin/console locator:demo:seed --tenant=tenant-local
php bin/console locator:demo:seed --file=/data/locator-demo.ndjson
```

The command will feed each record from the fixture into `AddressPipelineInterface::process()`
with `meta` containing `tenantId`, `fixtureId` and `tagList`.
