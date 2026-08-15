<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Tests\Service\Http;

use App\Locating\Infrastructure\Provider\Location\InMemoryProviderMetricSnapshotStore;
use App\Locating\ReadModel\Observability\Location\ProviderMetricSnapshot;
use App\Locating\Service\Http\Location\LocationMetricsHttpService;
use App\Locating\Service\Observability\Location\LocationMetricsExportService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class LocationMetricsHttpServiceTest extends TestCase
{
    public function testMetricsEndpointRendersPrometheusFormat(): void
    {
        $controller = new LocationMetricsHttpService(new LocationMetricsExportService(new InMemoryProviderMetricSnapshotStore([
            'address_pipeline' => new ProviderMetricSnapshot('address_pipeline', 2, 1, 15.0, 0.5),
        ])));
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
