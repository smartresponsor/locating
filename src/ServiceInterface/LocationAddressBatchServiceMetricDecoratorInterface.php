<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

use Smartresponsor\EntityInterface\AddressBatchJobInterface;

interface LocationAddressBatchServiceMetricDecoratorInterface
{
    public function createJob(string $tenantId, array $itemList): AddressBatchJobInterface;

    public function jobStatus(string $jobId): ?AddressBatchJobInterface;

    public function jobResultList(string $jobId): array;
}
