<?php

declare(strict_types=1);

namespace App\Locating\Tests\Controller\Http;

use App\Locating\Controller\Http\Location\GovernanceController;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceReport;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceSnapshot;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceReportServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceControllerTest extends TestCase
{
    public function testControllerReturnsGovernanceReportPayload(): void
    {
        /** @var LocationProviderGovernanceReportServiceInterface&MockObject $service */
        $service = $this->createMock(LocationProviderGovernanceReportServiceInterface::class);
        $service->method('report')->willReturn(new ProviderGovernanceReport('location', [
            'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.95, 48.0, true, 0.001),
        ]));

        $controller = new GovernanceController($service);
        $response = $controller(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        /** @var array{service:string, providerCount:int, providers:array<string, array<string, mixed>>} $payload */

        self::assertSame('location', $payload['service']);
        self::assertSame(1, $payload['providerCount']);
        self::assertArrayHasKey('legacy-suggest', $payload['providers']);
    }
}
