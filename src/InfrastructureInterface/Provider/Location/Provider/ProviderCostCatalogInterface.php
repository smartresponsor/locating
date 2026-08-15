<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Provider;

interface ProviderCostCatalogInterface
{
    public function cost(string $providerId, string $region, string $op): float;

    public function set(string $providerId, string $region, string $op, float $unit): void;
}
