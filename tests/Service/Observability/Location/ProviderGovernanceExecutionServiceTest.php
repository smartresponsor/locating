<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRemediationPlan;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRemediationPlanReport;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRemediationStep;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceExecutionService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceRemediationPlanServiceInterface;
use PHPUnit\Framework\TestCase;

final class LocationProviderGovernanceExecutionServiceTest extends TestCase
{
    public function testBuildsAcknowledgementAwareExecutionState(): void
    {
        $plans = new class () implements LocationProviderGovernanceRemediationPlanServiceInterface {
            public function report(): \App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRemediationPlanReportInterface
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
