<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

interface ProviderCostCatalogInterface
{
    public function cost(string $providerId, string $region, string $op): float;

    public function set(string $providerId, string $region, string $op, float $unit): void;
}
