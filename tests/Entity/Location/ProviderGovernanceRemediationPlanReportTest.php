<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\ProviderGovernanceRemediationPlan;
use App\Locating\Model\Location\ProviderGovernanceRemediationPlanReport;
use App\Locating\Model\Location\ProviderGovernanceRemediationStep;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceRemediationPlanReportTest extends TestCase
{
    public function testReportExportsProviderPlans(): void
    {
        $report = new ProviderGovernanceRemediationPlanReport('location', [
            'alpha' => new ProviderGovernanceRemediationPlan('alpha', 'suggest', 'degraded', 'deprioritize-provider', ['success-rate-below-threshold'], ['reduce-traffic-share'], [new ProviderGovernanceRemediationStep('reduce-traffic-share', 'high', 'Reduce traffic share.', 'routing-policy')]),
        ]);

        self::assertSame(1, $report->itemCount());
        self::assertSame('reduce-traffic-share', $report->toArray()['providers']['alpha']['steps'][0]['code']);
    }
}
