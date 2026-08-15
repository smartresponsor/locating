<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Gateway;

interface ProviderCostCatalogGatewayInterface
{
    public function cost(string $sourceKey, string $region, string $operation): float;
}
