<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Provider\Location;

use App\EntityInterface\Location\ProviderQuotaSignalInterface;

interface ProviderQuotaSignalReaderInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function read(string $sourceKey, string $operation, array $context = []): ProviderQuotaSignalInterface;
}
