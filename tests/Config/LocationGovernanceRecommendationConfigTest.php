<?php

declare(strict_types=1);

namespace Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceRecommendationConfigTest extends TestCase
{
    public function testGovernanceRecommendationRouteTargetsAppController(): void
    {
        $route = file_get_contents(__DIR__.'/../../config/routes/locator_governance_recommendations.yaml');

        self::assertIsString($route);
        self::assertStringContainsString('/location/governance/recommendations', $route);
        self::assertStringContainsString('App\Controller\Http\Location\GovernanceRecommendationController', $route);
    }
}
