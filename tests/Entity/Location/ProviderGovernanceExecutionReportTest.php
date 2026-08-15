<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\ProviderGovernanceExecutionItem;
use App\Locating\Model\Location\ProviderGovernanceExecutionReport;
use App\Locating\Model\Location\ProviderGovernanceExecutionStepStatus;
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
