<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Tests\Locator\Service;

use App\Entity\Locator\GeoPoint;
use App\InfrastructureInterface\Locator\AddressProviderBridgeInterface;
use App\Service\Locator\AddressProviderRouter;
use PHPUnit\Framework\TestCase;

final class AddressProviderRouterFailoverTest extends TestCase
{
    public function testSecondaryProviderIsUsedWhenPrimaryThrows(): void
    {
        $primary = new class() implements AddressProviderBridgeInterface {
            public function geocode(array $componentMap): ?GeoPoint
            {
                throw new \RuntimeException('provider failure');
            }

            public function providerKey(): string
            {
                return 'primary';
            }
        };

        $secondary = new class() implements AddressProviderBridgeInterface {
            public function geocode(array $componentMap): ?GeoPoint
            {
                return new GeoPoint(29.7604, -95.3698);
            }

            public function providerKey(): string
            {
                return 'secondary';
            }
        };

        $router = new AddressProviderRouter([$primary, $secondary]);

        $point = $router->geocode(['street' => 'Main Street']);

        self::assertInstanceOf(GeoPoint::class, $point);
        self::assertSame('secondary', $router->providerKey());
    }

    public function testNoProviderSuccessReturnsNull(): void
    {
        $failing = new class() implements AddressProviderBridgeInterface {
            public function geocode(array $componentMap): ?GeoPoint
            {
                throw new \RuntimeException('provider failure');
            }

            public function providerKey(): string
            {
                return 'failing';
            }
        };

        $router = new AddressProviderRouter([$failing]);

        $point = $router->geocode(['street' => 'Main Street']);

        self::assertNull($point);
        self::assertNull($router->providerKey());
    }
}
