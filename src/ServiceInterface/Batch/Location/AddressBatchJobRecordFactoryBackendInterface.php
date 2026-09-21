<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\ModelInterface\Location\AddressBatchJobRecordInterface;

interface AddressBatchJobRecordFactoryBackendInterface
{
    public function create(string $tenantId, int $totalCount): AddressBatchJobRecordInterface;
}
