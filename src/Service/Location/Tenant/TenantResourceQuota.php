<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Location\Tenant;

use App\Locating\ServiceInterface\Location\Tenant\TenantResourceQuotaInterface;

final class TenantResourceQuota implements TenantResourceQuotaInterface
{
    /** @var array<string, array<string, array{req:int,cost:float}>> */
    private array $limit = [];
    /** @var array<string, array<string, array{req:int,cost:float}>> */
    private array $use = [];

    public function set(string $tenantId, string $resource, int $reqLimit, float $costLimit = 0.0): void
    {
        $this->limit[$tenantId] = $this->limit[$tenantId] ?? [];
        $this->limit[$tenantId][$resource] = ['req' => $reqLimit, 'cost' => $costLimit];
        $this->use[$tenantId] = $this->use[$tenantId] ?? [];
        $this->use[$tenantId][$resource] = $this->use[$tenantId][$resource] ?? ['req' => 0, 'cost' => 0.0];
    }

    public function charge(string $tenantId, string $resource, float $costUnit = 0.0): bool
    {
        $lim = $this->limit[$tenantId][$resource] ?? null;
        if (null === $lim) {
            return true;
        }
        $u = $this->use[$tenantId][$resource] ?? ['req' => 0, 'cost' => 0.0];
        if ($u['req'] + 1 > $lim['req']) {
            return false;
        }
        if ($lim['cost'] > 0.0 && $u['cost'] + $costUnit > $lim['cost']) {
            return false;
        }
        ++$u['req'];
        $u['cost'] += $costUnit;
        $this->use[$tenantId][$resource] = $u;

        return true;
    }

    public function state(string $tenantId, string $resource): array
    {
        return $this->use[$tenantId][$resource] ?? ['req' => 0, 'cost' => 0.0];
    }
}
