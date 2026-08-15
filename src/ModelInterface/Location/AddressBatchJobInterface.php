<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ModelInterface\Location;

use App\Locating\Model\Location\AddressBatchJobStatus;

interface AddressBatchJobInterface
{
    public function jobId(): string;

    public function tenantId(): string;

    public function jobStatus(): AddressBatchJobStatus;

    public function totalCount(): int;

    public function processedCount(): int;

    public function createdAt(): \DateTimeImmutable;

    public function updatedAt(): \DateTimeImmutable;
}
