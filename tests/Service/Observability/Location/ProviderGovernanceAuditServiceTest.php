<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceExplanation;
use App\Entity\Location\ProviderGovernanceExplanationReport;
use App\Entity\Location\ProviderGovernanceRecommendation;
use App\Entity\Location\ProviderGovernanceRecommendationReport;
use App\Service\Observability\Location\ProviderGovernanceAuditService;
use App\ServiceInterface\Observability\Location\ProviderGovernanceExplanationServiceInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceRecommendationServiceInterface;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceAuditServiceTest extends TestCase
{
    public function testBuildsAuditDecisionFromExplanationAndRecommendation(): void
    {
        $explanations = new class implements ProviderGovernanceExplanationServiceInterface {
            public function report(): \App\EntityInterface\Location\ProviderGovernanceExplanationReportInterface
            {
                return new ProviderGovernanceExplanationReport('location', [
                    'alpha' => new ProviderGovernanceExplanation('alpha', 'suggest', 'degraded', ['success-rate-below-threshold: 0.850 < 0.900']),
                ]);
            }
        };
        $recommendations = new class implements ProviderGovernanceRecommendationServiceInterface {
            public function report(): \App\EntityInterface\Location\ProviderGovernanceRecommendationReportInterface
            {
                return new ProviderGovernanceRecommendationReport('location', [
                    'alpha' => new ProviderGovernanceRecommendation('alpha', 'suggest', 'degraded', ['reduce-traffic-share']),
                ]);
            }
        };

        $report = (new ProviderGovernanceAuditService($explanations, $recommendations))->report();

        self::assertSame('deprioritize-provider', $report->providers()['alpha']->decision());
    }
}
