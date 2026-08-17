<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Infrastructure\Provider\Location\ProviderCostCatalogGateway;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderCostCatalogBackendInterface;
use PHPUnit\Framework\TestCase;

final class ProviderCostCatalogGatewayTest extends TestCase
{
    public function testGatewayDelegatesToBackend(): void
    {
        $catalog = new class () implements ProviderCostCatalogBackendInterface {
            public function cost(string $providerId, string $region, string $op): float
            {
                return 'cheap' === $providerId ? 0.1 : 0.8;
            }
        };

        $gateway = new ProviderCostCatalogGateway($catalog);

        self::assertSame(0.1, $gateway->cost('cheap', 'US', 'suggest'));
        self::assertSame(0.8, $gateway->cost('premium', 'US', 'suggest'));
    }
}
