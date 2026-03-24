<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceSnapshot;
use App\Service\Observability\Location\ProviderGovernanceExplanationService;
use App\ServiceInterface\Observability\Location\ProviderGovernanceCatalogServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceExplanationServiceTest extends TestCase
{
    public function testServiceBuildsSeverityAndReasons(): void
    {
        /** @var ProviderGovernanceCatalogServiceInterface&MockObject $catalog */
        $catalog = $this->createMock(ProviderGovernanceCatalogServiceInterface::class);
        $catalog->method('catalog')->willReturn([
            'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.82, 650.0, false, 0.007),
        ]);

        $service = new ProviderGovernanceExplanationService($catalog);
        $report = $service->report();
        $item = $report->providers()['legacy-suggest'];

        self::assertSame('critical', $item->severity());
        self::assertContains('quota-denied', $item->reasons());
        self::assertTrue(count($item->reasons()) >= 3);
    }
}
