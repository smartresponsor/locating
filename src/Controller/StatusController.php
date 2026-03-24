<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Controller;

use Smartresponsor\ControllerInterface\StatusControllerInterface;
use Smartresponsor\InfrastructureInterface\MetricSnapshotProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Lightweight status endpoint for Locator with basic metric snapshot.
 */
final class StatusController implements StatusControllerInterface
{
    public function __construct(
        private MetricSnapshotProviderInterface $metricSnapshotProvider
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $metrics = $this->metricSnapshotProvider->snapshot();

        $status = 'ok';

        foreach ($metrics as $operation => $data) {
            $errorRate = 0.0;

            if (isset($data['errorRate'])) {
                $errorRate = (float)$data['errorRate'];
            }

            if ($errorRate > 0.005) {
                $status = 'degraded';
                break;
            }
        }

        $body = [
            'service' => 'locator',
            'status' => $status,
            'metrics' => $metrics,
        ];

        return new JsonResponse($body);
    }
}
