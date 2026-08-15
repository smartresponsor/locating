<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExplanation;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExplanationReport;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRecommendation;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRecommendationReport;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceAuditService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceRecommendationServiceInterface;
use PHPUnit\Framework\TestCase;

final class LocationProviderGovernanceAuditServiceTest extends TestCase
{
    public function testBuildsAuditDecisionFromExplanationAndRecommendation(): void
    {
        $explanations = new class () implements LocationProviderGovernanceExplanationServiceInterface {
            public function report(): \App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExplanationReportInterface
            {
                return new ProviderGovernanceExplanationReport('location', [
                    'alpha' => new ProviderGovernanceExplanation('alpha', 'suggest', 'degraded', ['success-rate-below-threshold: 0.850 < 0.900']),
                ]);
            }
        };
        $recommendations = new class () implements LocationProviderGovernanceRecommendationServiceInterface {
            public function report(): \App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRecommendationReportInterface
            {
                return new ProviderGovernanceRecommendationReport('location', [
                    'alpha' => new ProviderGovernanceRecommendation('alpha', 'suggest', 'degraded', ['reduce-traffic-share']),
                ]);
            }
        };

        $report = (new LocationProviderGovernanceAuditService($explanations, $recommendations))->report();

        self::assertSame('deprioritize-provider', $report->providers()['alpha']->decision());
    }
}
