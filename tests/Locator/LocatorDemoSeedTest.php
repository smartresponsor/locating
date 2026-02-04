<?php
declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;

use App\Infrastructure\Locator\LocatorFixtureReader;
use App\Service\Locator\AddressNormalizer;
use App\Service\Locator\AddressParser;
use App\Service\Locator\AddressPipeline;
use App\Service\Locator\AddressValidator;
use App\Service\Locator\LocatorDemoSeed;
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
