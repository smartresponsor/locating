<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Http;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAuditEntry;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAuditReport;
use App\Locating\Service\Http\Location\LocationGovernanceAuditHttpService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceAuditServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class LocationGovernanceAuditHttpServiceTest extends TestCase
{
    public function testReturnsAuditPayload(): void
    {
        $service = new class () implements LocationProviderGovernanceAuditServiceInterface {
            public function report(): \App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceAuditReportInterface
            {
                return new ProviderGovernanceAuditReport('location', [
                    'alpha' => new ProviderGovernanceAuditEntry('alpha', 'suggest', 'warning', ['latency-high'], ['monitor'], 'monitor-provider'),
                ]);
            }
        };

        $response = (new LocationGovernanceAuditHttpService($service))(new Request());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('location', $payload['service']);
        self::assertSame('monitor-provider', $payload['providers']['alpha']['decision']);
    }
}
