<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceRemediationPlan;
use App\Entity\Location\ProviderGovernanceRemediationPlanReport;
use App\Entity\Location\ProviderGovernanceRemediationStep;
use App\Service\Observability\Location\LocationProviderGovernanceExecutionService;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceRemediationPlanServiceInterface;
use PHPUnit\Framework\TestCase;

final class LocationProviderGovernanceExecutionServiceTest extends TestCase
{
    public function testBuildsAcknowledgementAwareExecutionState(): void
    {
        $plans = new class implements LocationProviderGovernanceRemediationPlanServiceInterface {
            public function report(): \App\EntityInterface\Location\ProviderGovernanceRemediationPlanReportInterface
            {
                return new ProviderGovernanceRemediationPlanReport('location', [
                    'alpha' => new ProviderGovernanceRemediationPlan(
                        'alpha',
                        'suggest',
                        'degraded',
                        'deprioritize-provider',
                        ['success-rate-below-threshold'],
                        ['reduce-traffic-share'],
                        [new ProviderGovernanceRemediationStep('reduce-traffic-share', 'high', 'Reduce traffic share.', 'routing-policy')],
                    ),
                ]);
            }
        };

        $report = (new LocationProviderGovernanceExecutionService($plans))->report();
        self::assertSame('pending-acknowledgement', $report->providers()['alpha']->acknowledgementState());
        self::assertTrue($report->providers()['alpha']->steps()[0]->acknowledgementRequired());
    }
}
