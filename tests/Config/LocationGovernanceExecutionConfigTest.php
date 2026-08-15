<?php

declare(strict_types=1);

namespace App\Locating\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceExecutionConfigTest extends TestCase
{
    public function testGovernanceExecutionRouteExists(): void
    {
        $contents = file_get_contents(__DIR__.'/../../config/routes/locator_governance_execution.yaml');
        self::assertIsString($contents);
        self::assertStringContainsString('/location/governance/execution', $contents);
        self::assertStringContainsString('LocationGovernanceExecutionHttpService', $contents);
    }
}
