<?php

declare(strict_types=1);

namespace App\InfrastructureInterface\Batch\Location;

interface AddressBatchJobRecordFactoryBackendInterface
{
    public function create(string $tenantId, int $totalCount): AddressBatchJobRecordInterface;
}
