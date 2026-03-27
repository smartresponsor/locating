<?php

declare(strict_types=1);

namespace App\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\ProviderCostCatalogLegacyInterface;
use App\InfrastructureInterface\Provider\Location\ProviderCostCatalogBackendInterface;

final class SmartresponsorProviderCostCatalogBackend implements ProviderCostCatalogBackendInterface
{
    public function __construct(private readonly ProviderCostCatalogLegacyInterface $catalog)
    {
    }

    public function cost(string $sourceKey, string $region, string $operation): float
    {
        return $this->catalog->cost($sourceKey, $region, $operation);
    }
}
