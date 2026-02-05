<?php
declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;

use Smartresponsor\Infrastructure\Locator\LocatorFixtureReader;
use Smartresponsor\Service\Locator\AddressNormalizer;
use Smartresponsor\Service\Locator\AddressParser;
use Smartresponsor\Service\Locator\AddressPipeline;
use Smartresponsor\Service\Locator\AddressValidator;
use Smartresponsor\Service\Locator\LocatorDemoSeed;
use PHPUnit\Framework\TestCase;

final class LocatorDemoSeedTest extends TestCase
{
    public function testSeedDemoProcessFixture(): void
    {
        $fixturePath = dirname(__DIR__, 2) . '/fixtures/locator-demo.ndjson';
        self::assertFileExists($fixturePath, 'locator-demo.ndjson fixture must exist for demo seed.');

        $fixtureReader = new LocatorFixtureReader();

        $pipeline = new AddressPipeline(
            new AddressParser(),
            new AddressNormalizer(),
            new AddressValidator()
        );

        $service = new LocatorDemoSeed($fixtureReader, $pipeline, $fixturePath);

        $count = $service->seedDemo('tenant-demo');

        self::assertGreaterThan(0, $count, 'Demo seed should process at least one record.');
    }
}
