<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderCostSignal;
use App\Locating\ReadModel\Observability\Location\ProviderHealthSignal;
use App\Locating\ReadModel\Observability\Location\ProviderQuotaSignal;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceCatalogService;
use App\Locating\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class LocationProviderGovernanceCatalogServiceTest extends TestCase
{
    public function testItBuildsGovernanceCatalog(): void
    {
        $service = new LocationProviderGovernanceCatalogService(
            new class () implements ProviderHealthSignalReaderInterface {
                public function read(string $sourceKey): \App\Locating\ReadModelInterface\Observability\Location\ProviderHealthSignalInterface
                {
                    return new ProviderHealthSignal($sourceKey, 0.95, 150.0);
                }
            },
            new class () implements ProviderQuotaSignalReaderInterface {
                public function read(string $sourceKey, string $operation, array $context = []): \App\Locating\ReadModelInterface\Observability\Location\ProviderQuotaSignalInterface
                {
                    return new ProviderQuotaSignal($sourceKey, $operation, true);
                }
            },
            new class () implements ProviderCostSignalReaderInterface {
                public function read(string $sourceKey, string $operation, array $context = []): \App\Locating\ReadModelInterface\Observability\Location\ProviderCostSignalInterface
                {
                    return new ProviderCostSignal($sourceKey, $operation, 'global', 0.31);
                }
            },
            [
                'legacy-suggest' => 'suggest',
                'legacy-reverse' => 'reverse',
            ],
        );

        $catalog = $service->catalog();

        self::assertCount(2, $catalog);
        self::assertSame('suggest', $catalog['legacy-suggest']->operation());
        self::assertSame(0.31, $catalog['legacy-reverse']->unitCost());
    }
}
