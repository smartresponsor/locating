<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Entity;

use App\EntityInterface\AddressBatchJobInterface;

final class AddressBatchJob implements AddressBatchJobInterface
{
    private AddressBatchJobStatus $status;

    private int $processedCount;

    private \DateTimeImmutable $createdAt;

    private \DateTimeImmutable $updatedAt;

    public function __construct(
        private string $jobId,
        private string $tenantId,
        private int $totalCount
    ) {
        $now = new \DateTimeImmutable('now');
        $this->status = AddressBatchJobStatus::PENDING;
        $this->processedCount = 0;
        $this->createdAt = $now;
        $this->updatedAt = $now;
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

    public function markRun(): void
    {
        $this->status = AddressBatchJobStatus::RUNNING;
        $this->touch();
    }

    public function markComplete(): void
    {
        $this->status = AddressBatchJobStatus::COMPLETED;
        $this->touch();
    }

    public function markFail(): void
    {
        $this->status = AddressBatchJobStatus::FAILED;
        $this->touch();
    }

    public function incrementProcessed(): void
    {
        $this->processedCount++;
        if ($this->processedCount >= $this->totalCount && $this->status !== AddressBatchJobStatus::FAILED) {
            $this->status = AddressBatchJobStatus::COMPLETED;
        }
        $this->touch();
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable('now');
    }
}
