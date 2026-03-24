<?php

declare(strict_types=1);

namespace App\Infrastructure\Batch\Location;

use App\InfrastructureInterface\Batch\Location\AddressBatchJobRecordFactoryBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobRecordInterface;
use Smartresponsor\Entity\Locator\AddressBatchJob as LegacyAddressBatchJob;

final class SmartresponsorAddressBatchJobRecordFactoryBackend implements AddressBatchJobRecordFactoryBackendInterface
{
    public function create(string $tenantId, int $totalCount): AddressBatchJobRecordInterface
    {
        return new SmartresponsorAddressBatchJobRecord(
            new LegacyAddressBatchJob(bin2hex(random_bytes(16)), $tenantId, $totalCount),
        );
    }
}
