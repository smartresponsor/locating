<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Tests\Status;

use App\Controller\MetricsController;
use App\Infrastructure\InMemoryMetricRecorder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class MetricsControllerTest extends TestCase
{
    public function testMetricsEndpointRendersPrometheusFormat(): void
    {
        $recorder = new InMemoryMetricRecorder();

        // Simulate a few operations
        $recorder->recordLatency('address_pipeline', 10.0);
        $recorder->incrementCounter('address_pipeline', 'ok');

        $recorder->recordLatency('address_pipeline', 20.0);
        $recorder->incrementCounter('address_pipeline', 'error');

        $controller = new MetricsController($recorder);

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

