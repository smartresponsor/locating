<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Observability\Location;

use App\Locating\Infrastructure\Provider\Location\InMemoryProviderMetricSnapshotStore;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceSnapshot;
use App\Locating\ReadModel\Observability\Location\ProviderMetricSnapshot;
use App\Locating\Service\Observability\Location\LocationStatusReportService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use PHPUnit\Framework\TestCase;

final class LocationStatusReportServiceTest extends TestCase
{
    public function testItBuildsStatusReportWithGovernanceSection(): void
    {
        $service = new LocationStatusReportService(
            new InMemoryProviderMetricSnapshotStore([
                'suggest' => new ProviderMetricSnapshot('suggest', 10, 0, 120.0, 0.0),
            ]),
            new class () implements LocationProviderGovernanceCatalogServiceInterface {
                public function catalog(): array
                {
                    return [
                        'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.98, 111.0, true, 0.25),
                    ];
                }
            },
        );

        $report = $service->report();

        self::assertSame('ok', $report->status());
        self::assertArrayHasKey('legacy-suggest', $report->governance());
        self::assertArrayHasKey('governance', $report->toArray());
    }
}
