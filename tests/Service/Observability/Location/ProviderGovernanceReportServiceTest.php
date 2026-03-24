<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceSnapshot;
use App\Service\Observability\Location\ProviderGovernanceReportService;
use App\ServiceInterface\Observability\Location\ProviderGovernanceCatalogServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceReportServiceTest extends TestCase
{
    public function testServiceBuildsGovernanceReport(): void
    {
        /** @var ProviderGovernanceCatalogServiceInterface&MockObject $catalog */
        $catalog = $this->createMock(ProviderGovernanceCatalogServiceInterface::class);
        $catalog->method('catalog')->willReturn([
            'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.97, 40.0, true, 0.001),
        ]);

        $service = new ProviderGovernanceReportService($catalog);
        $report = $service->report();

        self::assertSame('location', $report->service());
        self::assertSame(1, $report->providerCount());
        self::assertSame(0, $report->degradedCount());
    }
}
