<?php

declare(strict_types=1);

namespace Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationRoutingConfigTest extends TestCase
{
    public function testAddressSuggestRouteUsesCanonicalHttpLocationControllerAndPath(): void
    {
        $content = file_get_contents(__DIR__.'/../../config/routes/locator_address_suggest.yaml');
        self::assertIsString($content);
        self::assertStringContainsString('/location/address/suggest', $content);
        self::assertStringContainsString('App\\Controller\\Http\\Location\\AddressSuggestController', $content);
        self::assertStringNotContainsString('App\\Controller\\Locator\\AddressSuggestController', $content);
    }

    public function testAddressReverseRouteUsesCanonicalHttpLocationControllerAndPath(): void
    {
        $content = file_get_contents(__DIR__.'/../../config/routes/locator_reverse.php');
        self::assertIsString($content);
        self::assertStringContainsString('/location/address/reverse', $content);
        self::assertStringContainsString('App\\\\Controller\\\\Http\\\\Location\\\\AddressReverseController', $content);
        self::assertStringNotContainsString('App\\\\Controller\\\\Locator\\\\AddressReverseController', $content);
    }

    public function testStatusAndMetricsRoutesUseCanonicalHttpLocationControllersAndPaths(): void
    {
        $status = file_get_contents(__DIR__.'/../../config/routes/locator_status.yaml');
        $metrics = file_get_contents(__DIR__.'/../../config/routes/locator_metrics.yaml');

        self::assertIsString($status);
        self::assertIsString($metrics);
        self::assertStringContainsString('/location/status', $status);
        self::assertStringContainsString('App\\Controller\\Http\\Location\\StatusController', $status);
        self::assertStringContainsString('/location/metrics', $metrics);
        self::assertStringContainsString('App\\Controller\\Http\\Location\\MetricsController', $metrics);
    }
}
