<?php

declare(strict_types=1);

namespace App\Infrastructure\Batch\Location;

use App\InfrastructureInterface\Batch\Location\AddressBatchJobRecordInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobRepositoryBackendInterface;
use Smartresponsor\InfrastructureInterface\Locator\AddressBatchJobRepositoryInterface;

final class SmartresponsorAddressBatchJobRepositoryBackend implements AddressBatchJobRepositoryBackendInterface
{
    public function __construct(private readonly AddressBatchJobRepositoryInterface $repository)
    {
    }

    public function save(AddressBatchJobRecordInterface $job): void
    {
        if ($job instanceof SmartresponsorAddressBatchJobRecord) {
            $this->repository->save($job->inner());
        }
    }

    public function find(string $jobId): ?AddressBatchJobRecordInterface
    {
        $job = $this->repository->find($jobId);

        return null === $job ? null : new SmartresponsorAddressBatchJobRecord($job);
    }
}
