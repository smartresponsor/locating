<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\InfrastructureInterface\Locator;

use App\Bridge\Legacy\Entity\Location\AddressResultLegacyInterface;

interface AddressBatchResultStorageInterface
{
    public function appendResult(string $jobId, AddressResultLegacyInterface $result): void;

    /**
     * @return AddressResultLegacyInterface[]
     */
    public function resultList(string $jobId): array;
}
