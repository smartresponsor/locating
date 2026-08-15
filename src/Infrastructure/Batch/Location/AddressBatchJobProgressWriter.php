<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobProgressWriterInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobRepositoryBackendInterface;

final class AddressBatchJobProgressWriter implements AddressBatchJobProgressWriterInterface
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
