<?php

declare(strict_types=1);

namespace App\Locating\Tests\Controller\Http;

use App\Locating\Controller\Http\Location\GovernanceAcknowledgementController;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAcknowledgement;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAcknowledgementReport;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceAcknowledgementServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GovernanceAcknowledgementControllerTest extends TestCase
{
    public function testReturnsGovernanceAcknowledgementPayload(): void
    {
        $service = new class () implements LocationProviderGovernanceAcknowledgementServiceInterface {
            public function acknowledge(array $payload): \App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceAcknowledgementReportInterface
            {
                return new ProviderGovernanceAcknowledgementReport('location', [
                    'alpha:reduce-traffic-share' => new ProviderGovernanceAcknowledgement('alpha', 'reduce-traffic-share', 'approved', 'acknowledged', 'acknowledged', true, 'Proceed.'),
                ]);
            }
        };

        $request = new Request(content: json_encode(['acknowledgements' => ['alpha' => ['reduce-traffic-share' => ['outcome' => 'approved']]]], JSON_THROW_ON_ERROR));
        $response = (new GovernanceAcknowledgementController($service))($request);
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('location', $payload['service']);
        self::assertSame('acknowledged', $payload['acknowledgements']['alpha:reduce-traffic-share']['acknowledgementState']);
    }
}
