<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\InfrastructureInterface\Batch\Location;

interface AddressBatchResultReaderInterface
{
    /**
     * @return array<int,array<string,mixed>>
     */
    public function resultList(string $jobId): array;
}
