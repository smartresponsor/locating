<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Service\Locator;

use Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface;
use Smartresponsor\InfrastructureInterface\Locator\MetricRecorderInterface;
use Smartresponsor\ServiceInterface\Locator\LocationAddressBatchServiceInterface;

/**
 * Decorator that records metrics for LocationAddressBatchServiceInterface.
 */
final class LocationAddressBatchServiceMetricDecorator implements LocationAddressBatchServiceInterface
{
    public function __construct(
        private LocationAddressBatchServiceInterface $inner,
        private MetricRecorderInterface $metricRecorder
    ) {
    }

    public function createJob(string $tenantId, array $itemList): AddressBatchJobInterface
    {
        $start = microtime(true);

        try {
            $job = $this->inner->createJob($tenantId, $itemList);
        } catch (\Throwable $exception) {
            $durationMs = (microtime(true) - $start) * 1000.0;
            $this->metricRecorder->recordLatency('address_batch_create', $durationMs);
            $this->metricRecorder->incrementCounter('address_batch_create', 'error');

            throw $exception;
        }

        $durationMs = (microtime(true) - $start) * 1000.0;
        $this->metricRecorder->recordLatency('address_batch_create', $durationMs);
        $this->metricRecorder->incrementCounter('address_batch_create', 'ok');

        return $job;
    }

    public function jobStatus(string $jobId): ?AddressBatchJobInterface
    {
        return $this->inner->jobStatus($jobId);
    }

    public function jobResultList(string $jobId): array
    {
        return $this->inner->jobResultList($jobId);
    }
}
