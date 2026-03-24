<?php

declare(strict_types=1);

namespace Tests\Entity\Location;

use App\Entity\Location\ProviderGovernanceReport;
use App\Entity\Location\ProviderGovernanceSnapshot;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceReportTest extends TestCase
{
    public function testReportSummarizesProviders(): void
    {
        $report = new ProviderGovernanceReport('location', [
            'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.98, 42.5, true, 0.001),
            'legacy-reverse' => new ProviderGovernanceSnapshot('legacy-reverse', 'reverse', 0.82, 90.0, false, 0.002),
        ]);

        self::assertSame('location', $report->service());
        self::assertSame(2, $report->providerCount());
        self::assertSame(1, $report->degradedCount());
        self::assertArrayHasKey('providers', $report->toArray());
    }
}
