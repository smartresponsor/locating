<?php

declare(strict_types=1);

namespace App\Locating\Tests\Controller\Http;

use App\Locating\Controller\Http\Location\GovernanceExecutionController;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExecutionItem;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExecutionReport;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExecutionStepStatus;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExecutionServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceExecutionControllerTest extends TestCase
{
    public function testReturnsGovernanceExecutionPayload(): void
    {
        $service = new class () implements LocationProviderGovernanceExecutionServiceInterface {
            public function report(): \App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExecutionReportInterface
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
