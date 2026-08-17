<?php

declare(strict_types=1);

namespace App\Locating\Tests\Controller\Http;

use App\Locating\Controller\Http\Location\GovernanceExplanationController;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExplanation;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExplanationReport;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceExplanationControllerTest extends TestCase
{
    public function testControllerReturnsGovernanceExplanationPayload(): void
    {
        /** @var LocationProviderGovernanceExplanationServiceInterface&MockObject $service */
        $service = $this->createMock(LocationProviderGovernanceExplanationServiceInterface::class);
        $service->method('report')->willReturn(new ProviderGovernanceExplanationReport('location', [
            'legacy-suggest' => new ProviderGovernanceExplanation('legacy-suggest', 'suggest', 'warning', ['unit-cost-high']),
        ]));

        $controller = new GovernanceExplanationController($service);
        $response = $controller(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        /** @var array{service:string, providers:array<string, array{severity:string}>} $payload */

        self::assertSame('location', $payload['service']);
        self::assertSame('warning', $payload['providers']['legacy-suggest']['severity']);
    }
}
