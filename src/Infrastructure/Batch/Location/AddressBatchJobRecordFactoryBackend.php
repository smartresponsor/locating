<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobRecordFactoryBackendInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobRecordInterface;
use App\Locating\Model\Location\AddressBatchJob;
use App\Locating\Model\Location\AddressBatchJobStatus;

final class AddressBatchJobRecordFactoryBackend implements AddressBatchJobRecordFactoryBackendInterface
{
    public function create(string $tenantId, int $totalCount): AddressBatchJobRecordInterface
    {
        $now = new \DateTimeImmutable();

        return new AddressBatchJobRecord(
            new AddressBatchJob(
                bin2hex(random_bytes(16)),
                $tenantId,
                AddressBatchJobStatus::PENDING,
                max(0, $totalCount),
                0,
                $now,
                $now,
            ),
        );
    }
}
