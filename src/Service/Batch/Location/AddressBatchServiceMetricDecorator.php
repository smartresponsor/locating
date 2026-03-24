<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Batch\Location;

use App\EntityInterface\Location\AddressBatchJobInterface;
use App\InfrastructureInterface\Provider\Location\LocationMetricRecorderInterface;
use App\ServiceInterface\Batch\Location\AddressBatchServiceInterface;

final class AddressBatchServiceMetricDecorator implements AddressBatchServiceInterface
{
    public function __construct(
        private AddressBatchServiceInterface $inner,
        private LocationMetricRecorderInterface $metricRecorder,
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
