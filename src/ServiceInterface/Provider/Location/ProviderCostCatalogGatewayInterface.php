<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Provider\Location;

interface ProviderCostCatalogGatewayInterface
{
    public function cost(string $sourceKey, string $region, string $operation): float;
}
