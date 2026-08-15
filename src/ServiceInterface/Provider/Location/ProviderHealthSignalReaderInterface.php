<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Provider\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderHealthSignalInterface;

interface ProviderHealthSignalReaderInterface
{
    public function read(string $sourceKey): ProviderHealthSignalInterface;
}
