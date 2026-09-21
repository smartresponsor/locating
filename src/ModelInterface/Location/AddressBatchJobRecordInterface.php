<?php

declare(strict_types=1);

namespace App\Locating\ModelInterface\Location;

interface AddressBatchJobRecordInterface
{
    public function jobId(): string;

    public function tenantId(): string;

    public function jobStatusValue(): string;

    public function totalCount(): int;

    public function processedCount(): int;

    public function createdAt(): \DateTimeImmutable;

    public function updatedAt(): \DateTimeImmutable;

    public function markRun(): void;

    public function incrementProcessed(): void;
}
