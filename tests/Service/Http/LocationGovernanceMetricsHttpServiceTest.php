<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Http;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceMetricSet;
use App\Locating\Service\Http\Location\LocationGovernanceMetricsHttpService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceMetricsExportServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class LocationGovernanceMetricsHttpServiceTest extends TestCase
{
    public function testHttpServiceReturnsGovernanceMetricsPayload(): void
    {
        /** @var LocationProviderGovernanceMetricsExportServiceInterface&MockObject $service */
        $service = $this->createMock(LocationProviderGovernanceMetricsExportServiceInterface::class);
        $service->method('export')->willReturn(new ProviderGovernanceMetricSet('location', [
            '# HELP locator_provider_degraded Locator provider degraded state by source.',
            'locator_provider_degraded{source="legacy-suggest",operation="suggest"} 1',
        ]));

        $controller = new LocationGovernanceMetricsHttpService($service);
        $response = $controller(new Request());

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/plain; version=0.0.4', $response->headers->get('Content-Type'));
        self::assertStringContainsString('locator_provider_degraded', (string) $response->getContent());
    }
}
