<?php

declare(strict_types=1);

namespace App\Locating\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationRoutingConfigTest extends TestCase
{
    public function testAddressSuggestRouteIsDeclarativeAndPathOnly(): void
    {
        $content = file_get_contents(__DIR__.'/../../config/routes/locator_address_suggest.yaml');
        self::assertIsString($content);
        self::assertStringContainsString('/location/address/suggest', $content);
        self::assertStringContainsString('methods: [GET]', $content);
        self::assertStringNotContainsString('controller:', $content);
        self::assertStringNotContainsString('App\Locating\\Service\\', $content);
        self::assertStringNotContainsString('App\Locating\\Controller\\', $content);
    }

    public function testAddressReverseRouteIsDeclarativeAndPathOnly(): void
    {
        $content = file_get_contents(__DIR__.'/../../config/routes/locator_reverse.yaml');
        self::assertIsString($content);
        self::assertStringContainsString('/location/address/reverse', $content);
        self::assertStringContainsString('methods: [GET]', $content);
        self::assertStringNotContainsString('controller:', $content);
        self::assertStringNotContainsString('App\Locating\\Service\\', $content);
        self::assertStringNotContainsString('App\Locating\\Controller\\', $content);
    }

    public function testStatusAndMetricsRoutesAreDeclarativeAndPathOnly(): void
    {
        $status = file_get_contents(__DIR__.'/../../config/routes/locator_status.yaml');
        $metrics = file_get_contents(__DIR__.'/../../config/routes/locator_metrics.yaml');

        self::assertIsString($status);
        self::assertIsString($metrics);
        self::assertStringContainsString('/location/status', $status);
        self::assertStringContainsString('/location/metrics', $metrics);
        self::assertStringNotContainsString('controller:', $status);
        self::assertStringNotContainsString('controller:', $metrics);
    }
}
