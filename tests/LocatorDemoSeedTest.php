<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;

use Smartresponsor\Infrastructure\LocatorFixtureReader;
use Smartresponsor\Service\AddressNormalizer;
use Smartresponsor\Service\AddressParser;
use Smartresponsor\Service\AddressPipeline;
use Smartresponsor\Service\AddressValidator;
use Smartresponsor\Service\LocatorDemoSeed;
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
