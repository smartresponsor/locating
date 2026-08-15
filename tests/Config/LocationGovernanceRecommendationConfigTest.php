<?php

declare(strict_types=1);

namespace App\Locating\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceRecommendationConfigTest extends TestCase
{
    public function testGovernanceRecommendationRouteTargetsAppService(): void
    {
        $route = file_get_contents(__DIR__.'/../../config/routes/locator_governance_recommendations.yaml');

        self::assertIsString($route);
        self::assertStringContainsString('/location/governance/recommendations', $route);
        self::assertStringContainsString('App\Locating\Service\Http\Location\LocationGovernanceRecommendationHttpService', $route);
    }
}
