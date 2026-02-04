<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface AddressBatchServiceMetricDecoratorInterface
{
    public function createJob(string $tenantId, array $itemList): AddressBatchJobInterface;
    public function jobStatus(string $jobId): ?AddressBatchJobInterface;
    public function jobResultList(string $jobId): array;
}