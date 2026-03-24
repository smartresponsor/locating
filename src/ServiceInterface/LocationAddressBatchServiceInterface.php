<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

use Smartresponsor\EntityInterface\AddressBatchJobInterface;

interface LocationAddressBatchServiceInterface
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
