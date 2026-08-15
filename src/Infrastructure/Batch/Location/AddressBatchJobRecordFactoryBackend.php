<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobRecordFactoryBackendInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobRecordInterface;
use App\Locating\Model\Location\AddressBatchJob;

final class AddressBatchJobRecordFactoryBackend implements AddressBatchJobRecordFactoryBackendInterface
{
    public function create(string $tenantId, int $totalCount): AddressBatchJobRecordInterface
    {
        return new AddressBatchJobRecord(
            new AddressBatchJob(bin2hex(random_bytes(16)), $tenantId, $totalCount),
        );
    }
}
