<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\ProviderCostCatalogBackendInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderCostCatalogGatewayInterface;

final class ProviderCostCatalogGateway implements ProviderCostCatalogGatewayInterface
{
    public function __construct(private readonly ProviderCostCatalogBackendInterface $backend)
    {
    }

    public function cost(string $sourceKey, string $region, string $operation): float
    {
        return max(0.0, $this->backend->cost($sourceKey, $region, $operation));
    }
}
