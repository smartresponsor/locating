<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\ProviderCostCatalogLegacyInterface;
use App\Infrastructure\Provider\Location\SmartresponsorProviderCostCatalogBackend;
use PHPUnit\Framework\TestCase;

final class SmartresponsorProviderCostCatalogBackendTest extends TestCase
{
    public function testCostDelegatesToLegacyCatalog(): void
    {
        $backend = new SmartresponsorProviderCostCatalogBackend(new class implements ProviderCostCatalogLegacyInterface {
            public function cost(string $providerId, string $region, string $op): float
            {
                return 0.42;
            }

            public function set(string $providerId, string $region, string $op, float $unit): void
            {
            }
        });
        self::assertSame(0.42, $backend->cost('x', 'US', 'suggest'));
    }
}
