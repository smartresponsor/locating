<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Tests\Controller\Http;

use App\Locating\Controller\Http\Location\MetricsController;
use App\Locating\ReadModel\Observability\Location\ProviderMetricSnapshot;
use App\Locating\Service\Observability\Location\LocationMetricsExportService;
use App\Locating\Service\Provider\Location\InMemoryProviderMetricSnapshotStore;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class MetricsControllerTest extends TestCase
{
    public function testMetricsEndpointRendersPrometheusFormat(): void
    {
        $governance = $this->createStub(LocationProviderGovernanceCatalogServiceInterface::class);
        $governance->method('catalog')->willReturn([]);
        $controller = new MetricsController(new LocationMetricsExportService(new InMemoryProviderMetricSnapshotStore([
            'address_pipeline' => new ProviderMetricSnapshot('address_pipeline', 2, 1, 15.0, 0.5),
        ]), $governance));
        $response = $controller(new Request());

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/plain; version=0.0.4', $response->headers->get('Content-Type'));

        $body = (string) $response->getContent();

        self::assertStringContainsString('# HELP locator_request_total', $body);
        self::assertStringContainsString('locator_request_total{operation="address_pipeline"}', $body);
        self::assertStringContainsString('locator_request_error_total{operation="address_pipeline"}', $body);
        self::assertStringContainsString('locator_request_latency_avg_ms{operation="address_pipeline"}', $body);
        self::assertStringContainsString('locator_request_error_rate{operation="address_pipeline"}', $body);
    }
}
