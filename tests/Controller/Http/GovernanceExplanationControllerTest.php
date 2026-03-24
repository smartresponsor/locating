<?php

declare(strict_types=1);

namespace Tests\Controller\Http;

use App\Controller\Http\Location\GovernanceExplanationController;
use App\Entity\Location\ProviderGovernanceExplanation;
use App\Entity\Location\ProviderGovernanceExplanationReport;
use App\ServiceInterface\Observability\Location\ProviderGovernanceExplanationServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceExplanationControllerTest extends TestCase
{
    public function testControllerReturnsGovernanceExplanationPayload(): void
    {
        /** @var ProviderGovernanceExplanationServiceInterface&MockObject $service */
        $service = $this->createMock(ProviderGovernanceExplanationServiceInterface::class);
        $service->method('report')->willReturn(new ProviderGovernanceExplanationReport('location', [
            'legacy-suggest' => new ProviderGovernanceExplanation('legacy-suggest', 'suggest', 'warning', ['unit-cost-high']),
        ]));

        $controller = new GovernanceExplanationController($service);
        $response = $controller(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('location', $payload['service']);
        self::assertSame('warning', $payload['providers']['legacy-suggest']['severity']);
    }
}
