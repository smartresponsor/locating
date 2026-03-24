<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Batch\Location;

use App\EntityInterface\Location\AddressBatchJobInterface;

interface AddressBatchServiceInterface
{
    /**
     * @param array<int,array<string,mixed>> $itemList
     */
    public function createJob(string $tenantId, array $itemList): AddressBatchJobInterface;

    public function jobStatus(string $jobId): ?AddressBatchJobInterface;

    /**
     * @return array<int,array<string,mixed>>
     */
    public function jobResultList(string $jobId): array;
}
