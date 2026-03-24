<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\InfrastructureInterface\Provider\Location\ProviderCostCatalogGatewayInterface;
use App\Service\Provider\Location\LegacyProviderCostSignalReader;
use PHPUnit\Framework\TestCase;

final class LegacyProviderCostSignalReaderTest extends TestCase
{
    public function testReaderBuildsTypedSignalFromGateway(): void
    {
        $gateway = new class implements ProviderCostCatalogGatewayInterface {
            public function cost(string $sourceKey, string $region, string $operation): float
            {
                return 'cheap' === $sourceKey ? 0.05 : 0.9;
            }
        };

        $reader = new LegacyProviderCostSignalReader($gateway, 'global');
        $signal = $reader->read('cheap', 'suggest', ['countryCode' => 'us']);

        self::assertSame('cheap', $signal->sourceKey());
        self::assertSame('suggest', $signal->operation());
        self::assertSame('US', $signal->region());
        self::assertSame(0.05, $signal->unitCost());
    }
}
