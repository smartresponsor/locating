<?php

declare(strict_types=1);

namespace Tests\Entity\Location;

use App\Entity\Location\ProviderGovernanceExplanation;
use App\Entity\Location\ProviderGovernanceExplanationReport;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceExplanationReportTest extends TestCase
{
    public function testReportSerializesItems(): void
    {
        $report = new ProviderGovernanceExplanationReport('location', [
            'legacy-suggest' => new ProviderGovernanceExplanation('legacy-suggest', 'suggest', 'warning', ['unit-cost-high']),
        ]);

        self::assertSame(1, $report->itemCount());
        self::assertSame('warning', $report->toArray()['providers']['legacy-suggest']['severity']);
    }
}
