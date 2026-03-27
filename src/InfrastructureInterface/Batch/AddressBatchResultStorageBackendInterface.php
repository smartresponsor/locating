<?php

declare(strict_types=1);

namespace App\InfrastructureInterface\Batch\Location;

interface AddressBatchResultStorageBackendInterface
{
    /** @return list<AddressBatchResultRecordInterface> */
    public function resultList(string $jobId): array;

    public function appendResult(string $jobId, AddressBatchResultRecordInterface $result): void;
}
