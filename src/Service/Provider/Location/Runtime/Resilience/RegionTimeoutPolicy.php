<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Resilience;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience\RegionTimeoutPolicyInterface;

final class RegionTimeoutPolicy implements RegionTimeoutPolicyInterface
{
    /** @var array<string, array<string, array<string,int>>> prov=>region=>op=>ms */
    private array $m = [];

    public function add(string $providerId, string $region, string $op, int $timeoutMs): void
    {
        $this->m[$providerId][$region][$op] = max(1, $timeoutMs);
    }

    public function timeout(string $providerId, string $region, string $op, int $defaultMs): int
    {
        $p = $this->m[$providerId] ?? [];
        if (isset($p[$region][$op])) {
            return $p[$region][$op];
        }
        if (isset($p['*'][$op])) {
            return $p['*'][$op];
        }
        if (isset(($this->m['*'] ?? [])[$region][$op])) {
            return $this->m['*'][$region][$op];
        }

        return max(1, $defaultMs);
    }
}
