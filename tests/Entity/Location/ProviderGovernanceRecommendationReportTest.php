<?php

declare(strict_types=1);

namespace Tests\Entity\Location;

use App\Entity\Location\ProviderGovernanceRecommendation;
use App\Entity\Location\ProviderGovernanceRecommendationReport;
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
