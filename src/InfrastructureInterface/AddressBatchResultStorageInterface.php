<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\InfrastructureInterface;

use App\EntityInterface\AddressResultInterface;

interface AddressBatchResultStorageInterface
{
    public function appendResult(string $jobId, AddressResultInterface $result): void;

    /**
     * @return AddressResultInterface[]
     */
    public function resultList(string $jobId): array;
}
