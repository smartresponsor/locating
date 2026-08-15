<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\ProviderGovernanceExplanation;
use App\Locating\Model\Location\ProviderGovernanceExplanationReport;
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
