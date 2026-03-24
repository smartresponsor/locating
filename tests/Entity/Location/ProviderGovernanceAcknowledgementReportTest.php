<?php

declare(strict_types=1);

namespace Tests\Entity\Location;

use App\Entity\Location\ProviderGovernanceAcknowledgement;
use App\Entity\Location\ProviderGovernanceAcknowledgementReport;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceAcknowledgementReportTest extends TestCase
{
    public function testToArrayContainsAcknowledgementEntries(): void
    {
        $report = new ProviderGovernanceAcknowledgementReport('location', [
            'alpha:reduce-traffic-share' => new ProviderGovernanceAcknowledgement('alpha', 'reduce-traffic-share', 'approved', 'acknowledged', 'acknowledged', true, 'ok'),
        ]);

        $payload = $report->toArray();
        self::assertSame('location', $payload['service']);
        self::assertSame('acknowledged', $payload['acknowledgements']['alpha:reduce-traffic-share']['acknowledgementState']);
    }
}
