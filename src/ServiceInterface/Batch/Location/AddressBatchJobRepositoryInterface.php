<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\ModelInterface\Location\AddressBatchJobInterface;

interface AddressBatchJobRepositoryInterface
{
    public function save(AddressBatchJobInterface $job): void;

    public function find(string $jobId): ?AddressBatchJobInterface;
}
