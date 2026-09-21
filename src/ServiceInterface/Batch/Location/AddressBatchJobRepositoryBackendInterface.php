<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\ModelInterface\Location\AddressBatchJobRecordInterface;

interface AddressBatchJobRepositoryBackendInterface
{
    public function save(AddressBatchJobRecordInterface $job): void;

    public function find(string $jobId): ?AddressBatchJobRecordInterface;
}
