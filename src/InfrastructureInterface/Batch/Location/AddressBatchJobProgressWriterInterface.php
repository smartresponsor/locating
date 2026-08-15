<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\InfrastructureInterface\Batch\Location;

interface AddressBatchJobProgressWriterInterface
{
    public function markRun(string $jobId): bool;

    public function incrementProcessed(string $jobId): bool;
}
