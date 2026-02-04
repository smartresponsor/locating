<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\InfrastructureInterface\Locator;

use App\EntityInterface\Locator\AddressResultInterface;

interface AddressBatchResultStorageInterface
{
    public function appendResult(string $jobId, AddressResultInterface $result): void;

    /**
     * @return AddressResultInterface[]
     */
    public function resultList(string $jobId): array;
}
