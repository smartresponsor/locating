<?php

declare(strict_types=1);

namespace App\InfrastructureInterface\Provider\Location;

interface ProviderCostCatalogBackendInterface
{
    public function cost(string $sourceKey, string $region, string $operation): float;
}
