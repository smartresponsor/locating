<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Batch\Location;

use App\EntityInterface\Location\AddressBatchJobInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobRecordFactoryBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobRepositoryBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobStoreInterface;
use App\ServiceInterface\Batch\Location\AddressBatchJobFactoryInterface;

final class LegacyAddressBatchJobStore implements AddressBatchJobStoreInterface
{
    public function __construct(
        private AddressBatchJobRepositoryBackendInterface $jobRepository,
        private AddressBatchJobRecordFactoryBackendInterface $jobRecordFactory,
        private AddressBatchJobFactoryInterface $jobFactory,
    ) {
    }

    public function create(string $tenantId, int $totalCount): AddressBatchJobInterface
    {
        $jobRecord = $this->jobRecordFactory->create($tenantId, $totalCount);
        $this->jobRepository->save($jobRecord);

        return $this->jobFactory->create($jobRecord);
    }

    public function find(string $jobId): ?AddressBatchJobInterface
    {
        $job = $this->jobRepository->find($jobId);

        return null === $job ? null : $this->jobFactory->create($job);
    }
}
