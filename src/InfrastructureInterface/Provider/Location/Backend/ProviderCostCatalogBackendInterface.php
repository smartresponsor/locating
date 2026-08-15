<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Backend;

interface ProviderCostCatalogBackendInterface
{
    public function cost(string $sourceKey, string $region, string $operation): float;
}
