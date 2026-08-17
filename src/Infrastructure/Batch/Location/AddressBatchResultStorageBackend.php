<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultStorageBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Batch\AddressBatchResultStorageInterface;

final class AddressBatchResultStorageBackend implements AddressBatchResultStorageBackendInterface
{
    public function __construct(private readonly AddressBatchResultStorageInterface $storage)
    {
    }

    public function resultList(string $jobId): array
    {
        $recordList = [];
        foreach ($this->storage->resultList($jobId) as $result) {
            $recordList[] = new AddressBatchResultRecord($result);
        }

        return $recordList;
    }

    public function appendResult(string $jobId, AddressBatchResultRecordInterface $result): void
    {
        if ($result instanceof AddressBatchResultRecord) {
            $this->storage->appendResult($jobId, $result->inner());
        }
    }
}
