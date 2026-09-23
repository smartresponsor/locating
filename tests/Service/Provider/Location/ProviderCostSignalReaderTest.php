<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Service\Provider\Location\ProviderCostSignalReader;
use App\Locating\ServiceInterface\Provider\Location\ProviderCostCatalogGatewayInterface;
use PHPUnit\Framework\TestCase;

final class ProviderCostSignalReaderTest extends TestCase
{
    public function testReaderBuildsTypedSignalFromGateway(): void
    {
        $gateway = new class () implements ProviderCostCatalogGatewayInterface {
            public function cost(string $sourceKey, string $region, string $operation): float
            {
                return 'cheap' === $sourceKey ? 0.05 : 0.9;
            }
        };

        $reader = new ProviderCostSignalReader($gateway, 'global');
        $signal = $reader->read('cheap', 'suggest', ['countryCode' => 'us']);

        self::assertSame('cheap', $signal->sourceKey());
        self::assertSame('suggest', $signal->operation());
        self::assertSame('US', $signal->region());
        self::assertSame(0.05, $signal->unitCost());
    }
}
