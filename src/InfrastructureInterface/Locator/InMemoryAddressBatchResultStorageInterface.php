<?php
declare(strict_types=1);

namespace App\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface InMemoryAddressBatchResultStorageInterface
{
    public function appendResult(string $jobId, AddressResultInterface $result): void;
    public function resultList(string $jobId): array;
}