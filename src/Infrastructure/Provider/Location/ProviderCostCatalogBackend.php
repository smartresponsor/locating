<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderCostCatalogBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Provider\ProviderCostCatalogInterface;

final class ProviderCostCatalogBackend implements ProviderCostCatalogBackendInterface
{
    public function __construct(private readonly ProviderCostCatalogInterface $catalog)
    {
    }

    public function cost(string $sourceKey, string $region, string $operation): float
    {
        return $this->catalog->cost($sourceKey, $region, $operation);
    }
}
