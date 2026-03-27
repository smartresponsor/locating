<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceSnapshot;
use App\Entity\Location\ProviderMetricSnapshot;
use App\Infrastructure\Provider\Location\InMemoryProviderMetricSnapshotStore;
use App\Service\Observability\Location\LocationMetricsExportService;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use PHPUnit\Framework\TestCase;

final class LocationMetricsExportServiceTest extends TestCase
{
    public function testItExportsGovernanceMetrics(): void
    {
        $service = new LocationMetricsExportService(
            new InMemoryProviderMetricSnapshotStore([
                'suggest' => new ProviderMetricSnapshot('suggest', 10, 1, 120.0, 0.1),
            ]),
            new class implements LocationProviderGovernanceCatalogServiceInterface {
                public function catalog(): array
                {
                    return [
                        'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.9, 140.0, true, 0.5),
                    ];
                }
            },
        );

        $export = $service->exportPrometheus();

        self::assertStringContainsString('locator_provider_success_rate{source="legacy-suggest",operation="suggest"} 0.90000', $export);
        self::assertStringContainsString('locator_provider_unit_cost{source="legacy-suggest",operation="suggest"} 0.50000', $export);
    }
}
