<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\InfrastructureInterface\Locator;

use Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface;

interface AddressBatchJobRepositoryInterface
{
    public function save(AddressBatchJobInterface $job): void;

    public function find(string $jobId): ?AddressBatchJobInterface;
}
