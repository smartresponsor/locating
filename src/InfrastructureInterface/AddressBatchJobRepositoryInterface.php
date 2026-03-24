<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\InfrastructureInterface;

use App\EntityInterface\AddressBatchJobInterface;

interface AddressBatchJobRepositoryInterface
{
    public function save(AddressBatchJobInterface $job): void;

    public function find(string $jobId): ?AddressBatchJobInterface;
}
