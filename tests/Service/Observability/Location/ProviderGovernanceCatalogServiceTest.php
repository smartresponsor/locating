<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderCostSignal;
use App\Entity\Location\ProviderHealthSignal;
use App\Entity\Location\ProviderQuotaSignal;
use App\Service\Observability\Location\ProviderGovernanceCatalogService;
use App\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface;
use App\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use App\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceCatalogServiceTest extends TestCase
{
    public function testItBuildsGovernanceCatalog(): void
    {
        $service = new ProviderGovernanceCatalogService(
            new class implements ProviderHealthSignalReaderInterface {
                public function read(string $sourceKey): \App\EntityInterface\Location\ProviderHealthSignalInterface
                {
                    return new ProviderHealthSignal($sourceKey, 0.95, 150.0);
                }
            },
            new class implements ProviderQuotaSignalReaderInterface {
                public function read(string $sourceKey, string $operation, array $context = []): \App\EntityInterface\Location\ProviderQuotaSignalInterface
                {
                    return new ProviderQuotaSignal($sourceKey, $operation, true);
                }
            },
            new class implements ProviderCostSignalReaderInterface {
                public function read(string $sourceKey, string $operation, array $context = []): \App\EntityInterface\Location\ProviderCostSignalInterface
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
