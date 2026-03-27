<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceExplanation;
use App\Entity\Location\ProviderGovernanceExplanationReport;
use App\Service\Observability\Location\LocationProviderGovernanceRecommendationService;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class LocationProviderGovernanceRecommendationServiceTest extends TestCase
{
    public function testServiceMapsReasonsToActions(): void
    {
        /** @var LocationProviderGovernanceExplanationServiceInterface&MockObject $explanations */
        $explanations = $this->createMock(LocationProviderGovernanceExplanationServiceInterface::class);
        $explanations->method('report')->willReturn(new ProviderGovernanceExplanationReport('location', [
            'legacy-suggest' => new ProviderGovernanceExplanation(
                'legacy-suggest',
                'suggest',
                'critical',
                ['quota-denied', 'unit-cost-high: 0.007000 > 0.005000'],
            ),
        ]));

        $service = new LocationProviderGovernanceRecommendationService($explanations);
        $report = $service->report();
        $payload = $report->toArray();

        self::assertSame('critical', $payload['providers']['legacy-suggest']['severity']);
        self::assertContains('reroute-to-available-provider', $payload['providers']['legacy-suggest']['recommendations']);
        self::assertContains('review-provider-budget', $payload['providers']['legacy-suggest']['recommendations']);
    }
}
