<?php

declare(strict_types=1);

namespace App\Locating\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceRemediationPlanConfigTest extends TestCase
{
    public function testGovernanceRemediationPlanRouteExists(): void
    {
        $contents = file_get_contents(__DIR__.'/../../config/routes/locator_governance_remediation_plans.yaml');
        self::assertIsString($contents);
        self::assertStringContainsString('/location/governance/remediation-plans', $contents);
        self::assertStringContainsString('LocationGovernanceRemediationPlanHttpService', $contents);
    }
}
