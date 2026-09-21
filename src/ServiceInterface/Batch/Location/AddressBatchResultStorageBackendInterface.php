<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\ModelInterface\Location\AddressBatchResultRecordInterface;

interface AddressBatchResultStorageBackendInterface
{
    /** @return list<AddressBatchResultRecordInterface> */
    public function resultList(string $jobId): array;

    public function appendResult(string $jobId, AddressBatchResultRecordInterface $result): void;
}
