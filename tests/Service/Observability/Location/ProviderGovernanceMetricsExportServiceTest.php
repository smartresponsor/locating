<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceSnapshot;
use App\Service\Observability\Location\ProviderGovernanceMetricsExportService;
use App\ServiceInterface\Observability\Location\ProviderGovernanceCatalogServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceMetricsExportServiceTest extends TestCase
{
    public function testServiceBuildsGovernanceMetrics(): void
    {
        /** @var ProviderGovernanceCatalogServiceInterface&MockObject $catalog */
        $catalog = $this->createMock(ProviderGovernanceCatalogServiceInterface::class);
        $catalog->method('catalog')->willReturn([
            'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.82, 58.0, false, 0.004),
        ]);

        $service = new ProviderGovernanceMetricsExportService($catalog);
        $metrics = $service->export()->toPrometheus();

        self::assertStringContainsString('locator_provider_success_rate{source="legacy-suggest",operation="suggest"}', $metrics);
        self::assertStringContainsString('locator_provider_quota_allowed{source="legacy-suggest",operation="suggest"} 0', $metrics);
        self::assertStringContainsString('locator_provider_unit_cost{source="legacy-suggest",operation="suggest"} 0.00400', $metrics);
        self::assertStringContainsString('locator_provider_degraded{source="legacy-suggest",operation="suggest"} 1', $metrics);
    }
}
