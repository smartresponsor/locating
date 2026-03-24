<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\InfrastructureInterface\Locator;

use App\Bridge\Legacy\Entity\Location\AddressBatchJobLegacyInterface;

interface AddressBatchJobRepositoryInterface
{
    public function save(AddressBatchJobLegacyInterface $job): void;

    public function find(string $jobId): ?AddressBatchJobLegacyInterface;
}
