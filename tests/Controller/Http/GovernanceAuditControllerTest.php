<?php

declare(strict_types=1);

namespace Tests\Controller\Http;

use App\Controller\Http\Location\GovernanceAuditController;
use App\Entity\Location\ProviderGovernanceAuditEntry;
use App\Entity\Location\ProviderGovernanceAuditReport;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceAuditServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceAuditControllerTest extends TestCase
{
    public function testReturnsAuditPayload(): void
    {
        $service = new class implements LocationProviderGovernanceAuditServiceInterface {
            public function report(): \App\EntityInterface\Location\ProviderGovernanceAuditReportInterface
            {
                return new ProviderGovernanceAuditReport('location', [
                    'alpha' => new ProviderGovernanceAuditEntry('alpha', 'suggest', 'warning', ['latency-high'], ['monitor'], 'monitor-provider'),
                ]);
            }
        };

        $response = (new GovernanceAuditController($service))(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('location', $payload['service']);
        self::assertSame('monitor-provider', $payload['providers']['alpha']['decision']);
    }
}
