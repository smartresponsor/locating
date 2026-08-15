<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceSnapshot;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceReportService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class LocationProviderGovernanceReportServiceTest extends TestCase
{
    public function testServiceBuildsGovernanceReport(): void
    {
        /** @var LocationProviderGovernanceCatalogServiceInterface&MockObject $catalog */
        $catalog = $this->createMock(LocationProviderGovernanceCatalogServiceInterface::class);
        $catalog->method('catalog')->willReturn([
            'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.97, 40.0, true, 0.001),
        ]);

        $service = new LocationProviderGovernanceReportService($catalog);
        $report = $service->report();

        self::assertSame('location', $report->service());
        self::assertSame(1, $report->providerCount());
        self::assertSame(0, $report->degradedCount());
    }
}
