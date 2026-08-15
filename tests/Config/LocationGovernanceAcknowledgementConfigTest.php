<?php

declare(strict_types=1);

namespace App\Locating\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceAcknowledgementConfigTest extends TestCase
{
    public function testGovernanceAcknowledgementRouteExists(): void
    {
        $contents = file_get_contents(__DIR__.'/../../config/routes/locator_governance_acknowledgements.yaml');
        self::assertIsString($contents);
        self::assertStringContainsString('/location/governance/acknowledgements', $contents);
        self::assertStringContainsString('LocationGovernanceAcknowledgementHttpService', $contents);
    }
}
