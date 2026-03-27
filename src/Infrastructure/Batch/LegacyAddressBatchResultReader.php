<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Batch\Location;

use App\InfrastructureInterface\Batch\Location\AddressBatchResultReaderInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultStorageBackendInterface;

final class LegacyAddressBatchResultReader implements AddressBatchResultReaderInterface
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
