<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\ModelInterface\Location\AddressBatchJobInterface;

interface AddressBatchJobStoreInterface
{
    public function create(string $tenantId, int $totalCount): AddressBatchJobInterface;

    public function find(string $jobId): ?AddressBatchJobInterface;
}
