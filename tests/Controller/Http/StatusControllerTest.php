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
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class StatusControllerTest extends TestCase
{
    public function testStatusReturnsOkWithLocationServiceName(): void
    {
        $governance = $this->createStub(LocationProviderGovernanceCatalogServiceInterface::class);
        $governance->method('catalog')->willReturn([]);
        $controller = new StatusController(new LocationStatusReportService(new InMemoryProviderMetricSnapshotStore([
            'address_pipeline' => new ProviderMetricSnapshot('address_pipeline', 1, 0, 10.0, 0.0),
        ]), $governance));
        $response = $controller(new Request());

        self::assertSame(200, $response->getStatusCode());

        $data = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        /** @var array{service:string, status:string, metrics:array<string, mixed>} $data */

        self::assertSame('location', $data['service']);
        self::assertSame('ok', $data['status']);
        self::assertArrayHasKey('metrics', $data);
        self::assertArrayHasKey('address_pipeline', $data['metrics']);
    }
}
