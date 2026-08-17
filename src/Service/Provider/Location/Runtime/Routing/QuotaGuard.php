<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Routing;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Routing\QuotaGuardInterface;

/** Simple per-tenant cost/request quota guard */
final class QuotaGuard implements QuotaGuardInterface
{
    /** @var array<string, array{req:int, cost:float}> */
    private array $limit = [];

    /** @var array<string, array{req:int, cost:float}> */
    private array $use = [];

    public function setLimit(string $tenantId, int $reqLimit, float $costLimit): void
    {
        $this->limit[$tenantId] = ['req' => $reqLimit, 'cost' => $costLimit];
        $this->use[$tenantId] = $this->use[$tenantId] ?? ['req' => 0, 'cost' => 0.0];
    }

    public function charge(string $tenantId, float $costUnit = 0.0): bool
    {
        $lim = $this->limit[$tenantId] ?? null;
        if (null === $lim) {
            return true;
        }
        $u = $this->use[$tenantId];
        if ($u['req'] + 1 > $lim['req']) {
            return false;
        }
        if ($u['cost'] + $costUnit > $lim['cost']) {
            return false;
        }
        ++$u['req'];
        $u['cost'] += $costUnit;
        $this->use[$tenantId] = $u;

        return true;
    }

    /** @return array{req:int, cost:float} */
    public function state(string $tenantId): array
    {
        return $this->use[$tenantId] ?? ['req' => 0, 'cost' => 0.0];
    }
}
