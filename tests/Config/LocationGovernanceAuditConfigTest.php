<?php

declare(strict_types=1);

namespace App\Locating\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceAuditConfigTest extends TestCase
{
    public function testGovernanceAuditRouteExists(): void
    {
        $contents = file_get_contents(__DIR__.'/../../config/routes/locator_governance_audit.yaml');
        self::assertIsString($contents);
        self::assertStringContainsString('/location/governance/audit', $contents);
        self::assertStringContainsString('LocationGovernanceAuditHttpService', $contents);
    }
}
