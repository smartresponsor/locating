<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultReaderInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultStorageBackendInterface;

final class AddressBatchResultReader implements AddressBatchResultReaderInterface
{
    public function __construct(
        private AddressBatchResultStorageBackendInterface $resultStorage,
    ) {
    }

    public function resultList(string $jobId): array
    {
        return array_map(
            static fn (AddressBatchResultRecordInterface $result): array => $result->toArray(),
            $this->resultStorage->resultList($jobId),
        );
    }
}
