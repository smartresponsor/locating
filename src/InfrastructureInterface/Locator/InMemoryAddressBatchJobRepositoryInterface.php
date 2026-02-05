<?php
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface InMemoryAddressBatchJobRepositoryInterface
{
    public function save(AddressBatchJobInterface $job): void;
    public function find(string $jobId): ?AddressBatchJobInterface;
}