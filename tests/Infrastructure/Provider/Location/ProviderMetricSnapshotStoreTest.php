<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Service\Provider\Location\ProviderMetricSnapshotStore;
use App\Locating\ServiceInterface\Provider\Location\ProviderMetricSnapshotBackendInterface;
use PHPUnit\Framework\TestCase;

final class ProviderMetricSnapshotStoreTest extends TestCase
{
    public function testSnapshotMapsMetricsIntoAppReadModel(): void
    {
        $backend = new class () implements ProviderMetricSnapshotBackendInterface {
            public function snapshot(): array
            {
                return [
                    'address_pipeline' => [
                        'count' => 2,
                        'errorCount' => 1,
                        'avgMs' => 15.5,
                        'errorRate' => 0.5,
                    ],
                ];
            }
        };

        $store = new ProviderMetricSnapshotStore($backend);
        $snapshot = $store->snapshot();

        self::assertArrayHasKey('address_pipeline', $snapshot);
        self::assertSame('address_pipeline', $snapshot['address_pipeline']->operation());
        self::assertSame(2, $snapshot['address_pipeline']->count());
        self::assertSame(1, $snapshot['address_pipeline']->errorCount());
    }
}
