<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderCostCatalogLegacyInterface
{
    public function cost(string $providerId, string $region, string $op): float;

    public function set(string $providerId, string $region, string $op, float $unit): void;
}
