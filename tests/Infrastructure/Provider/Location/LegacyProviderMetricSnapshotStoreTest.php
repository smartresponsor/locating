<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Tests\Infrastructure\Provider\Location;

use App\Infrastructure\Provider\Location\LegacyProviderMetricSnapshotStore;
use PHPUnit\Framework\TestCase;
use Smartresponsor\Infrastructure\Locator\InMemoryMetricRecorder;

final class LegacyProviderMetricSnapshotStoreTest extends TestCase
{
    public function testSnapshotMapsLegacyMetricsIntoAppReadModel(): void
    {
        $recorder = new InMemoryMetricRecorder();
        $recorder->recordLatency('address_pipeline', 10.0);
        $recorder->incrementCounter('address_pipeline', 'ok');
        $recorder->recordLatency('address_pipeline', 20.0);
        $recorder->incrementCounter('address_pipeline', 'error');

        $store = new LegacyProviderMetricSnapshotStore($recorder);
        $snapshot = $store->snapshot();

        self::assertArrayHasKey('address_pipeline', $snapshot);
        self::assertSame('address_pipeline', $snapshot['address_pipeline']->operation());
        self::assertSame(2, $snapshot['address_pipeline']->count());
        self::assertSame(1, $snapshot['address_pipeline']->errorCount());
    }
}
