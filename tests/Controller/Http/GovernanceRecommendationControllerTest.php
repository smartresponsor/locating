<?php

declare(strict_types=1);

namespace Tests\Controller\Http;

use App\Controller\Http\Location\GovernanceRecommendationController;
use App\Entity\Location\ProviderGovernanceRecommendation;
use App\Entity\Location\ProviderGovernanceRecommendationReport;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceRecommendationServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceRecommendationControllerTest extends TestCase
{
    public function testControllerReturnsGovernanceRecommendationPayload(): void
    {
        /** @var LocationProviderGovernanceRecommendationServiceInterface&MockObject $service */
        $service = $this->createMock(LocationProviderGovernanceRecommendationServiceInterface::class);
        $service->method('report')->willReturn(new ProviderGovernanceRecommendationReport('location', [
            'legacy-suggest' => new ProviderGovernanceRecommendation(
                'legacy-suggest',
                'suggest',
                'warning',
                ['lower-provider-priority'],
            ),
        ]));

        $controller = new GovernanceRecommendationController($service);
        $response = $controller(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('location', $payload['service']);
        self::assertSame('lower-provider-priority', $payload['providers']['legacy-suggest']['recommendations'][0]);
    }
}
