<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\EntityInterface;

use Smartresponsor\Entity\AddressBatchJobStatus;

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
