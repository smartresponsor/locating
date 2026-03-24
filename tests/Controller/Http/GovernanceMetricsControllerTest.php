<?php

declare(strict_types=1);

namespace Tests\Controller\Http;

use App\Controller\Http\Location\GovernanceMetricsController;
use App\Entity\Location\ProviderGovernanceMetricSet;
use App\ServiceInterface\Observability\Location\ProviderGovernanceMetricsExportServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceMetricsControllerTest extends TestCase
{
    public function testControllerReturnsGovernanceMetricsPayload(): void
    {
        /** @var ProviderGovernanceMetricsExportServiceInterface&MockObject $service */
        $service = $this->createMock(ProviderGovernanceMetricsExportServiceInterface::class);
        $service->method('export')->willReturn(new ProviderGovernanceMetricSet('location', [
            '# HELP locator_provider_degraded Locator provider degraded state by source.',
            'locator_provider_degraded{source="legacy-suggest",operation="suggest"} 1',
        ]));

        $controller = new GovernanceMetricsController($service);
        $response = $controller(new Request());

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/plain; version=0.0.4', $response->headers->get('Content-Type'));
        self::assertStringContainsString('locator_provider_degraded', (string) $response->getContent());
    }
}
