<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Provider\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderCostSignalInterface;

interface ProviderCostSignalReaderInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function read(string $sourceKey, string $operation, array $context = []): ProviderCostSignalInterface;
}
