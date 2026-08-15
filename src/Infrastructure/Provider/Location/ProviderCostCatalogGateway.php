<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderCostCatalogBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Gateway\ProviderCostCatalogGatewayInterface;

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
