<?php

declare(strict_types=1);

namespace Tests\Entity\Location;

use App\Entity\Location\ProviderGovernanceExecutionItem;
use App\Entity\Location\ProviderGovernanceExecutionReport;
use App\Entity\Location\ProviderGovernanceExecutionStepStatus;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceExecutionReportTest extends TestCase
{
    public function testExportsExecutionReport(): void
    {
        $report = new ProviderGovernanceExecutionReport('location', [
            'alpha' => new ProviderGovernanceExecutionItem(
                'alpha',
                'deprioritize-provider',
                'degraded',
                'pending-acknowledgement',
                [new ProviderGovernanceExecutionStepStatus('reduce-traffic-share', 'high', 'routing-policy', 'awaiting-operator-ack', true)],
            ),
        ]);

        self::assertSame('location', $report->service());
        self::assertSame('awaiting-operator-ack', $report->toArray()['providers']['alpha']['steps'][0]['status']);
    }
}
