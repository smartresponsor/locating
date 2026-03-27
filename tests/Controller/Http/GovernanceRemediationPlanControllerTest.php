<?php

declare(strict_types=1);

namespace Tests\Controller\Http;

use App\Controller\Http\Location\GovernanceRemediationPlanController;
use App\Entity\Location\ProviderGovernanceRemediationPlan;
use App\Entity\Location\ProviderGovernanceRemediationPlanReport;
use App\Entity\Location\ProviderGovernanceRemediationStep;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceRemediationPlanServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceRemediationPlanControllerTest extends TestCase
{
    public function testReturnsRemediationPlanPayload(): void
    {
        $service = new class implements LocationProviderGovernanceRemediationPlanServiceInterface {
            public function report(): \App\EntityInterface\Location\ProviderGovernanceRemediationPlanReportInterface
            {
                return new ProviderGovernanceRemediationPlanReport('location', [
                    'alpha' => new ProviderGovernanceRemediationPlan('alpha', 'suggest', 'warning', 'monitor-provider', ['latency-high'], ['investigate-provider-latency'], [new ProviderGovernanceRemediationStep('investigate-provider-latency', 'medium', 'Investigate provider latency.', 'operations')]),
                ]);
            }
        };

        $response = (new GovernanceRemediationPlanController($service))(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('location', $payload['service']);
        self::assertSame('investigate-provider-latency', $payload['providers']['alpha']['steps'][0]['code']);
    }
}
