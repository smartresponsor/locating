<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Model\Location;

use App\Locating\ModelInterface\Location\AddressBatchJobInterface;

final class AddressBatchJob implements AddressBatchJobInterface
{
    public function __construct(
        private readonly string $jobId,
        private readonly string $tenantId,
        private readonly AddressBatchJobStatus $status,
        private readonly int $totalCount,
        private readonly int $processedCount,
        private readonly \DateTimeImmutable $createdAt,
        private readonly \DateTimeImmutable $updatedAt,
    ) {
    }

    public function jobId(): string
    {
        return $this->jobId;
    }

    public function tenantId(): string
    {
        return $this->tenantId;
    }

    public function jobStatus(): AddressBatchJobStatus
    {
        return $this->status;
    }

    public function totalCount(): int
    {
        return $this->totalCount;
    }

    public function processedCount(): int
    {
        return $this->processedCount;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
