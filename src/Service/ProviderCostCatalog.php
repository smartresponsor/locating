<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class ProviderCostCatalog implements ProviderCostCatalogInterface {
    /** @var array<string, array<string, array<string,float>>> */
    private array $map = [];
    public function cost(string $providerId, string $region, string $op): float {
        return (float)($this->map[$providerId][$region][$op] ?? 1.0);
    }
    public function set(string $providerId, string $region, string $op, float $unit): void {
        $this->map[$providerId] = $this->map[$providerId] ?? [];
        $this->map[$providerId][$region] = $this->map[$providerId][$region] ?? [];
        $this->map[$providerId][$region][$op] = max(0.0, $unit);
    }
}
