<?php

declare(strict_types=1);

namespace App\Infrastructure\Batch\Location;

use App\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultStorageBackendInterface;
use Smartresponsor\InfrastructureInterface\Locator\AddressBatchResultStorageInterface;

final class SmartresponsorAddressBatchResultStorageBackend implements AddressBatchResultStorageBackendInterface
{
    public function __construct(private readonly AddressBatchResultStorageInterface $storage)
    {
    }

    public function resultList(string $jobId): array
    {
        return array_map(
            static fn ($result) => new SmartresponsorAddressBatchResultRecord($result),
            $this->storage->resultList($jobId),
        );
    }

    public function appendResult(string $jobId, AddressBatchResultRecordInterface $result): void
    {
        if ($result instanceof SmartresponsorAddressBatchResultRecord) {
            $this->storage->appendResult($jobId, $result->inner());
        }
    }
}
