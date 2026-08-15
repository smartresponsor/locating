<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Http;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceReport;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceSnapshot;
use App\Locating\Service\Http\Location\LocationGovernanceHttpService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceReportServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class LocationGovernanceHttpServiceTest extends TestCase
{
    public function testHttpServiceReturnsGovernanceReportPayload(): void
    {
        /** @var LocationProviderGovernanceReportServiceInterface&MockObject $service */
        $service = $this->createMock(LocationProviderGovernanceReportServiceInterface::class);
        $service->method('report')->willReturn(new ProviderGovernanceReport('location', [
            'legacy-suggest' => new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.95, 48.0, true, 0.001),
        ]));

        $controller = new LocationGovernanceHttpService($service);
        $response = $controller(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('location', $payload['service']);
        self::assertSame(1, $payload['providerCount']);
        self::assertArrayHasKey('legacy-suggest', $payload['providers']);
    }
}
