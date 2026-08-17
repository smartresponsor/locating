<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Http;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExplanation;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExplanationReport;
use App\Locating\Service\Http\Location\LocationGovernanceExplanationHttpService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class LocationGovernanceExplanationHttpServiceTest extends TestCase
{
    public function testHttpServiceReturnsGovernanceExplanationPayload(): void
    {
        /** @var LocationProviderGovernanceExplanationServiceInterface&MockObject $service */
        $service = $this->createMock(LocationProviderGovernanceExplanationServiceInterface::class);
        $service->method('report')->willReturn(new ProviderGovernanceExplanationReport('location', [
            'legacy-suggest' => new ProviderGovernanceExplanation('legacy-suggest', 'suggest', 'warning', ['unit-cost-high']),
        ]));

        $controller = new LocationGovernanceExplanationHttpService($service);
        $response = $controller(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        /** @var array{service:string, providers:array<string, array{severity:string}>} $payload */

        self::assertSame('location', $payload['service']);
        self::assertSame('warning', $payload['providers']['legacy-suggest']['severity']);
    }
}
