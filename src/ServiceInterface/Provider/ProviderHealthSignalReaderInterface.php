<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Provider\Location;

use App\EntityInterface\Location\ProviderHealthSignalInterface;

interface ProviderHealthSignalReaderInterface
{
    public function read(string $sourceKey): ProviderHealthSignalInterface;
}
