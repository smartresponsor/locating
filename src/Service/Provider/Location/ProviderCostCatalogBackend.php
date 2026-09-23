<?php

declare(strict_types=1);

namespace App\Locating\Service\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Provider\ProviderCostCatalogInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderCostCatalogBackendInterface;

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
