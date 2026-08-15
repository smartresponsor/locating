<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Tests\Controller\Http;

use App\Locating\Controller\Http\Location\StatusController;
use App\Locating\Infrastructure\Provider\Location\InMemoryProviderMetricSnapshotStore;
use App\Locating\ReadModel\Observability\Location\ProviderMetricSnapshot;
use App\Locating\Service\Observability\Location\LocationStatusReportService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class StatusControllerTest extends TestCase
{
    public function testStatusReturnsOkWithLocationServiceName(): void
    {
        $controller = new StatusController(new LocationStatusReportService(new InMemoryProviderMetricSnapshotStore([
            'address_pipeline' => new ProviderMetricSnapshot('address_pipeline', 1, 0, 10.0, 0.0),
        ])));
        $response = $controller(new Request());

        self::assertSame(200, $response->getStatusCode());

        $data = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('location', $data['service'] ?? null);
        self::assertSame('ok', $data['status'] ?? null);
        self::assertArrayHasKey('metrics', $data);
        self::assertArrayHasKey('address_pipeline', $data['metrics']);
    }
}
