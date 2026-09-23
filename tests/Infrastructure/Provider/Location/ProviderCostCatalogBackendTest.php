<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Service\Provider\Location\ProviderCostCatalogBackend;
use App\Locating\ServiceInterface\Provider\Location\ProviderCostCatalogInterface;
use PHPUnit\Framework\TestCase;

final class ProviderCostCatalogBackendTest extends TestCase
{
    public function testCostDelegatesToCatalog(): void
    {
        $backend = new ProviderCostCatalogBackend(new class () implements ProviderCostCatalogInterface {
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
