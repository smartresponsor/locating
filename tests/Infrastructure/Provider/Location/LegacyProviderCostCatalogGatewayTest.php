<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\ProviderCostCatalogLegacyInterface;
use App\Infrastructure\Provider\Location\LegacyProviderCostCatalogGateway;
use PHPUnit\Framework\TestCase;

final class LegacyProviderCostCatalogGatewayTest extends TestCase
{
    public function testGatewayDelegatesToLegacyCatalog(): void
    {
        $catalog = new class implements ProviderCostCatalogLegacyInterface {
            public function cost(string $providerId, string $region, string $op): float
            {
                return 'cheap' === $providerId ? 0.1 : 0.8;
            }

            public function set(string $providerId, string $region, string $op, float $unit): void
            {
            }
        };

        $gateway = new LegacyProviderCostCatalogGateway($catalog);

        self::assertSame(0.1, $gateway->cost('cheap', 'US', 'suggest'));
        self::assertSame(0.8, $gateway->cost('premium', 'US', 'suggest'));
    }
}
