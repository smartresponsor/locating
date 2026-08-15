<?php

declare(strict_types=1);

namespace App\Locating\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceExplanationConfigTest extends TestCase
{
    public function testGovernanceExplanationRouteTargetsAppService(): void
    {
        $route = file_get_contents(__DIR__.'/../../config/routes/locator_governance_explanations.yaml');

        self::assertIsString($route);
        self::assertStringContainsString('/location/governance/explanations', $route);
        self::assertStringContainsString('App\Locating\\Service\\Http\\Location\\LocationGovernanceExplanationHttpService', $route);
    }
}
