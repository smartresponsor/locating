<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Batch;

use App\Locating\ModelInterface\Location\AddressReverseResultInterface;

interface AddressBatchResultStorageInterface
{
    public function appendResult(string $jobId, AddressReverseResultInterface $result): void;

    /**
     * @return AddressReverseResultInterface[]
     */
    public function resultList(string $jobId): array;
}
