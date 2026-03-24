<?php

declare(strict_types=1);

namespace Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceConfigTest extends TestCase
{
    public function testGovernanceRouteAndServiceAliasExist(): void
    {
        $routes = (string) file_get_contents(__DIR__.'/../../config/routes/locator_governance.yaml');
        $services = (string) file_get_contents(__DIR__.'/../../config/services.php');

        self::assertStringContainsString('/location/governance', $routes);
        self::assertStringContainsString('GovernanceController', $routes);
        self::assertStringContainsString('ProviderGovernanceReportServiceInterface::class', $services);
        self::assertStringContainsString('ProviderGovernanceReportService::class', $services);
    }
}
