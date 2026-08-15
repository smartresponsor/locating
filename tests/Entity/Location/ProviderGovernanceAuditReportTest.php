<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\ProviderGovernanceAuditEntry;
use App\Locating\Model\Location\ProviderGovernanceAuditReport;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceAuditReportTest extends TestCase
{
    public function testToArrayContainsAuditProviders(): void
    {
        $report = new ProviderGovernanceAuditReport('location', [
            'alpha' => new ProviderGovernanceAuditEntry('alpha', 'suggest', 'critical', ['quota-denied'], ['increase-provider-quota'], 'escalate-governance-review'),
        ]);

        self::assertSame('location', $report->service());
        self::assertSame(1, $report->itemCount());
        self::assertSame('critical', $report->toArray()['providers']['alpha']['severity']);
    }
}
