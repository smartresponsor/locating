<?php

declare(strict_types=1);

namespace App\Locating\Service\Batch\Location;

use App\Locating\Model\Location\Batch\AddressBatchJobRecord;
use App\Locating\ModelInterface\Location\AddressBatchJobRecordInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobRepositoryBackendInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobRepositoryInterface;

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
