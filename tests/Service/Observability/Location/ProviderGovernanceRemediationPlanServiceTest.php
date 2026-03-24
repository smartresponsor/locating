<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceAuditEntry;
use App\Entity\Location\ProviderGovernanceAuditReport;
use App\Service\Observability\Location\ProviderGovernanceRemediationPlanService;
use App\ServiceInterface\Observability\Location\ProviderGovernanceAuditServiceInterface;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceRemediationPlanServiceTest extends TestCase
{
    public function testBuildsStructuredStepsFromAuditRecommendations(): void
    {
        $audit = new class implements ProviderGovernanceAuditServiceInterface {
            public function report(): \App\EntityInterface\Location\ProviderGovernanceAuditReportInterface
            {
                return new ProviderGovernanceAuditReport('location', [
                    'alpha' => new ProviderGovernanceAuditEntry('alpha', 'suggest', 'degraded', ['success-rate-below-threshold: 0.850 < 0.900'], ['reduce-traffic-share', 'investigate-provider-failures'], 'deprioritize-provider'),
                ]);
            }
        };

        $report = (new ProviderGovernanceRemediationPlanService($audit))->report();
        self::assertSame('deprioritize-provider', $report->providers()['alpha']->decision());
        self::assertSame('reduce-traffic-share', $report->providers()['alpha']->steps()[0]->code());
    }
}
