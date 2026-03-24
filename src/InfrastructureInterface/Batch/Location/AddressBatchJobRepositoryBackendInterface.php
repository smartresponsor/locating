<?php

declare(strict_types=1);

namespace App\InfrastructureInterface\Batch\Location;

interface AddressBatchJobRepositoryBackendInterface
{
    public function save(AddressBatchJobRecordInterface $job): void;

    public function find(string $jobId): ?AddressBatchJobRecordInterface;
}
