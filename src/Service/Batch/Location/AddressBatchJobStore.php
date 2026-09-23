<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Batch\Location;

use App\Locating\FactoryInterface\Batch\Location\AddressBatchJobFactoryInterface;
use App\Locating\ModelInterface\Location\AddressBatchJobInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobRecordFactoryBackendInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobRepositoryBackendInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobStoreInterface;

final class AddressBatchJobStore implements AddressBatchJobStoreInterface
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
