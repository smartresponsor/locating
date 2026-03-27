<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceSnapshot;
use App\Service\Observability\Location\LocationProviderGovernanceExplanationService;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class LocationProviderGovernanceExplanationServiceTest extends TestCase
{
    public function testServiceBuildsSeverityAndReasons(): void
    {
        /** @var LocationProviderGovernanceCatalogServiceInterface&MockObject $catalog */
        $catalog = $this->createMock(LocationProviderGovernanceCatalogServiceInterface::class);
        $catalog->method('catalog')->willReturn([
            'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.82, 650.0, false, 0.007),
        ]);

        $service = new LocationProviderGovernanceExplanationService($catalog);
        $report = $service->report();
        $item = $report->providers()['legacy-suggest'];

        self::assertSame('critical', $item->severity());
        self::assertContains('quota-denied', $item->reasons());
        self::assertTrue(count($item->reasons()) >= 3);
    }
}
