<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Batch\Location;

use App\InfrastructureInterface\Batch\Location\AddressBatchJobProgressWriterInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobRepositoryBackendInterface;

final class LegacyAddressBatchJobProgressWriter implements AddressBatchJobProgressWriterInterface
{
    public function __construct(
        private AddressBatchJobRepositoryBackendInterface $jobRepository,
    ) {
    }

    public function markRun(string $jobId): bool
    {
        $job = $this->jobRepository->find($jobId);
        if (null === $job) {
            return false;
        }

        $job->markRun();
        $this->jobRepository->save($job);

        return true;
    }

    public function incrementProcessed(string $jobId): bool
    {
        $job = $this->jobRepository->find($jobId);
        if (null === $job) {
            return false;
        }

        $job->incrementProcessed();
        $this->jobRepository->save($job);

        return true;
    }
}
