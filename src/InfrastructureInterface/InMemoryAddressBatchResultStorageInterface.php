<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\InfrastructureInterface;

/**
 */

interface InMemoryAddressBatchResultStorageInterface
{
    public function appendResult(string $jobId, AddressResultInterface $result): void;
    public function resultList(string $jobId): array;
}