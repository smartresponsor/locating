<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceSnapshot;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceExplanationService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
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
