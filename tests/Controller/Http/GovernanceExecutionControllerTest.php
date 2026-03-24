<?php

declare(strict_types=1);

namespace Tests\Controller\Http;

use App\Controller\Http\Location\GovernanceExecutionController;
use App\Entity\Location\ProviderGovernanceExecutionItem;
use App\Entity\Location\ProviderGovernanceExecutionReport;
use App\Entity\Location\ProviderGovernanceExecutionStepStatus;
use App\ServiceInterface\Observability\Location\ProviderGovernanceExecutionServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceExecutionControllerTest extends TestCase
{
    public function testReturnsGovernanceExecutionPayload(): void
    {
        $service = new class implements ProviderGovernanceExecutionServiceInterface {
            public function report(): \App\EntityInterface\Location\ProviderGovernanceExecutionReportInterface
            {
                return new ProviderGovernanceExecutionReport('location', [
                    'alpha' => new ProviderGovernanceExecutionItem(
                        'alpha',
                        'deprioritize-provider',
                        'degraded',
                        'pending-acknowledgement',
                        [new ProviderGovernanceExecutionStepStatus('reduce-traffic-share', 'high', 'routing-policy', 'awaiting-operator-ack', true)],
                    ),
                ]);
            }
        };

        $response = (new GovernanceExecutionController($service))(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('location', $payload['service']);
        self::assertSame('pending-acknowledgement', $payload['providers']['alpha']['acknowledgementState']);
    }
}
