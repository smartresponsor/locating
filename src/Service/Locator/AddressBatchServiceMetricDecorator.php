<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Service\Locator;

use App\EntityInterface\Locator\AddressBatchJobInterface;
use App\InfrastructureInterface\Locator\MetricRecorderInterface;
use App\ServiceInterface\Locator\AddressBatchServiceInterface;

/**
 * Decorator that records metrics for AddressBatchServiceInterface.
 */
final class AddressBatchServiceMetricDecorator implements AddressBatchServiceInterface
{
    private AddressBatchServiceInterface $inner;

    private MetricRecorderInterface $metricRecorder;

    public function __construct(AddressBatchServiceInterface $inner, MetricRecorderInterface $metricRecorder)
    {
        $this->inner = $inner;
        $this->metricRecorder = $metricRecorder;
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
