<?php

declare(strict_types=1);

namespace Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationGovernanceMetricsConfigTest extends TestCase
{
    public function testGovernanceMetricsRouteAndServiceAliasExist(): void
    {
        $routes = (string) file_get_contents(__DIR__.'/../../config/routes/locator_governance_metrics.yaml');
        $services = (string) file_get_contents(__DIR__.'/../../config/services.php');

        self::assertStringContainsString('/location/governance/metrics', $routes);
        self::assertStringContainsString('GovernanceMetricsController', $routes);
        self::assertStringContainsString('ProviderGovernanceMetricsExportServiceInterface::class', $services);
        self::assertStringContainsString('ProviderGovernanceMetricsExportService::class', $services);
    }
}
