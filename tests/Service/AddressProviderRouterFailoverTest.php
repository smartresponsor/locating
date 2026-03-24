<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Tests\Service;

use App\Entity\GeoPoint;
use App\InfrastructureInterface\AddressProviderBridgeInterface;
use App\Service\AddressProviderRouter;
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
