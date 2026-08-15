<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAuditEntry;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAuditReport;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceRemediationPlanService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceAuditServiceInterface;
use PHPUnit\Framework\TestCase;

final class LocationProviderGovernanceRemediationPlanServiceTest extends TestCase
{
    public function testBuildsStructuredStepsFromAuditRecommendations(): void
    {
        $audit = new class () implements LocationProviderGovernanceAuditServiceInterface {
            public function report(): \App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceAuditReportInterface
            {
                return new ProviderGovernanceAuditReport('location', [
                    'alpha' => new ProviderGovernanceAuditEntry('alpha', 'suggest', 'degraded', ['success-rate-below-threshold: 0.850 < 0.900'], ['reduce-traffic-share', 'investigate-provider-failures'], 'deprioritize-provider'),
                ]);
            }
        };

        $report = (new LocationProviderGovernanceRemediationPlanService($audit))->report();
        self::assertSame('deprioritize-provider', $report->providers()['alpha']->decision());
        self::assertSame('reduce-traffic-share', $report->providers()['alpha']->steps()[0]->code());
    }
}
