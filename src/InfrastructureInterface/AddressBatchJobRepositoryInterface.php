<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\InfrastructureInterface;

use Smartresponsor\EntityInterface\AddressBatchJobInterface;

interface AddressBatchJobRepositoryInterface
{
    public function save(AddressBatchJobInterface $job): void;

    public function find(string $jobId): ?AddressBatchJobInterface;
}
