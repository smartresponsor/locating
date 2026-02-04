<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\EntityInterface\Locator;

use App\Entity\Locator\AddressBatchJobStatus;

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
