<?php

declare(strict_types=1);

namespace App\Locating\Tests\Controller\Http;

use App\Locating\Controller\Http\Location\GovernanceRemediationPlanController;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRemediationPlan;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRemediationPlanReport;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceRemediationStep;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceRemediationPlanServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceRemediationPlanControllerTest extends TestCase
{
    public function testReturnsRemediationPlanPayload(): void
    {
        $service = new class () implements LocationProviderGovernanceRemediationPlanServiceInterface {
            public function report(): \App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRemediationPlanReportInterface
            {
                return new ProviderGovernanceRemediationPlanReport('location', [
                    'alpha' => new ProviderGovernanceRemediationPlan('alpha', 'suggest', 'warning', 'monitor-provider', ['latency-high'], ['investigate-provider-latency'], [new ProviderGovernanceRemediationStep('investigate-provider-latency', 'medium', 'Investigate provider latency.', 'operations')]),
                ]);
            }
        };

        $response = (new GovernanceRemediationPlanController($service))(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        /** @var array{service:string, providers:array<string, array{steps:list<array{code:string}>}>} $payload */
        self::assertSame('location', $payload['service']);
        self::assertSame('investigate-provider-latency', $payload['providers']['alpha']['steps'][0]['code']);
    }
}
