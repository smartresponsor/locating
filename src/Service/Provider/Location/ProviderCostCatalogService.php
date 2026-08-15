<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Provider\ProviderCostCatalogInterface;

final class ProviderCostCatalogService implements ProviderCostCatalogInterface
{
    /** @var array<string, array<string, array<string,float>>> */
    private array $map = [];

    public function cost(string $providerId, string $region, string $op): float
    {
        return (float) ($this->map[$providerId][$region][$op] ?? 1.0);
    }

    public function set(string $providerId, string $region, string $op, float $unit): void
    {
        $this->map[$providerId] = $this->map[$providerId] ?? [];
        $this->map[$providerId][$region] = $this->map[$providerId][$region] ?? [];
        $this->map[$providerId][$region][$op] = max(0.0, $unit);
    }
}
