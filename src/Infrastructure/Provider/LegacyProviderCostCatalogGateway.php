<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Provider\Location;

use App\InfrastructureInterface\Provider\Location\ProviderCostCatalogBackendInterface;
use App\InfrastructureInterface\Provider\Location\ProviderCostCatalogGatewayInterface;

final class LegacyProviderCostCatalogGateway implements ProviderCostCatalogGatewayInterface
{
    public function __construct(private readonly ProviderCostCatalogBackendInterface $backend)
    {
    }

    public function cost(string $sourceKey, string $region, string $operation): float
    {
        return max(0.0, $this->backend->cost($sourceKey, $region, $operation));
    }
}
