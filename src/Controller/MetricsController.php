<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Controller;

use Smartresponsor\ControllerInterface\MetricsControllerInterface;
use Smartresponsor\InfrastructureInterface\MetricSnapshotProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Prometheus-style metrics endpoint for Locator.
 */
final class MetricsController implements MetricsControllerInterface
{
    public function __construct(
        private MetricSnapshotProviderInterface $metricSnapshotProvider
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $snapshot = $this->metricSnapshotProvider->snapshot();

        $lines = [];

        $lines[] = '# HELP locator_request_total Total Locator requests per operation.';
        $lines[] = '# TYPE locator_request_total counter';
        $lines[] = '# HELP locator_request_error_total Total Locator failed requests per operation.';
        $lines[] = '# TYPE locator_request_error_total counter';
        $lines[] = '# HELP locator_request_latency_avg_ms Average Locator latency in milliseconds per operation.';
        $lines[] = '# TYPE locator_request_latency_avg_ms gauge';
        $lines[] = '# HELP locator_request_error_rate Locator error rate per operation.';
        $lines[] = '# TYPE locator_request_error_rate gauge';

        foreach ($snapshot as $operation => $metric) {
            $count = (int)($metric['count'] ?? 0);
            $errorCount = (int)($metric['errorCount'] ?? 0);
            $avgMs = (float)($metric['avgMs'] ?? 0.0);
            $errorRate = (float)($metric['errorRate'] ?? 0.0);

            $op = (string)$operation;

            $lines[] = sprintf(
                'locator_request_total{operation="%s"} %d',
                $op,
                $count
            );
            $lines[] = sprintf(
                'locator_request_error_total{operation="%s"} %d',
                $op,
                $errorCount
            );
            $lines[] = sprintf(
                'locator_request_latency_avg_ms{operation="%s"} %.3f',
                $op,
                $avgMs
            );
            $lines[] = sprintf(
                'locator_request_error_rate{operation="%s"} %.5f',
                $op,
                $errorRate
            );
        }

        $body = implode("\n", $lines) . "\n";

        return new Response(
            $body,
            Response::HTTP_OK,
            ['Content-Type' => 'text/plain; version=0.0.4']
        );
    }
}

