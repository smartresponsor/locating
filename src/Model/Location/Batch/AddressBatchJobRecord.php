<?php

declare(strict_types=1);

namespace App\Locating\Model\Location\Batch;

use App\Locating\ModelInterface\Location\AddressBatchJobInterface;
use App\Locating\ModelInterface\Location\AddressBatchJobRecordInterface;

final class AddressBatchJobRecord implements AddressBatchJobRecordInterface
{
    public function __construct(private readonly AddressBatchJobInterface $inner)
    {
    }

    public function inner(): AddressBatchJobInterface
    {
        return $this->inner;
    }

    public function jobId(): string
    {
        return $this->inner->jobId();
    }

    public function tenantId(): string
    {
        return $this->inner->tenantId();
    }

    public function jobStatusValue(): string
    {
        return $this->inner->jobStatus()->value;
    }

    public function totalCount(): int
    {
        return $this->inner->totalCount();
    }

    public function processedCount(): int
    {
        return $this->inner->processedCount();
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->inner->createdAt();
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->inner->updatedAt();
    }

    public function markRun(): void
    {
        if (method_exists($this->inner, 'markRun')) {
            $this->inner->markRun();
        }
    }

    public function incrementProcessed(): void
    {
        if (method_exists($this->inner, 'incrementProcessed')) {
            $this->inner->incrementProcessed();
        }
    }
}
