<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobRecordInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobRepositoryBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Batch\AddressBatchJobRepositoryInterface;

final class AddressBatchJobRepositoryBackend implements AddressBatchJobRepositoryBackendInterface
{
    public function __construct(private readonly AddressBatchJobRepositoryInterface $repository)
    {
    }

    public function save(AddressBatchJobRecordInterface $job): void
    {
        if ($job instanceof AddressBatchJobRecord) {
            $this->repository->save($job->inner());
        }
    }

    public function find(string $jobId): ?AddressBatchJobRecordInterface
    {
        $job = $this->repository->find($jobId);

        return null === $job ? null : new AddressBatchJobRecord($job);
    }
}
