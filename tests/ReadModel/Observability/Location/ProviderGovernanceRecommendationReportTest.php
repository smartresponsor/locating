<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRecommendation;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRecommendationReport;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceRecommendationReportTest extends TestCase
{
    public function testReportExposesRecommendations(): void
    {
        $report = new ProviderGovernanceRecommendationReport('location', [
            'legacy-suggest' => new ProviderGovernanceRecommendation(
                'legacy-suggest',
                'suggest',
                'warning',
                ['lower-provider-priority', 'review-provider-budget'],
            ),
        ]);

        self::assertSame(1, $report->itemCount());
        self::assertSame('review-provider-budget', $report->toArray()['providers']['legacy-suggest']['recommendations'][1]);
    }
}
