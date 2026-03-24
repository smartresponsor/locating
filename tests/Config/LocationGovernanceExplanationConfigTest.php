<?php

declare(strict_types=1);

namespace Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceExplanationConfigTest extends TestCase
{
    public function testGovernanceExplanationRouteTargetsAppController(): void
    {
        $route = file_get_contents(__DIR__.'/../../config/routes/locator_governance_explanations.yaml');

        self::assertIsString($route);
        self::assertStringContainsString('/location/governance/explanations', $route);
        self::assertStringContainsString('App\\Controller\\Http\\Location\\GovernanceExplanationController', $route);
    }
}
