<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Entity\Locator;
/** Simple per-tenant cost/request quota guard */
final class QuotaGuard {
    private array $limit = []; // tenant => {req, cost}
    private array $use = [];   // tenant => {req, cost}
    public function setLimit(string $tenantId, int $reqLimit, float $costLimit): void {
        $this->limit[$tenantId] = ['req'=>$reqLimit, 'cost'=>$costLimit];
        $this->use[$tenantId] = $this->use[$tenantId] ?? ['req'=>0, 'cost'=>0.0];
    }
    public function charge(string $tenantId, float $costUnit=0.0): bool {
        $lim = $this->limit[$tenantId] ?? null;
        if ($lim === null) { return true; }
        $u = $this->use[$tenantId];
        if ($u['req'] + 1 > $lim['req']) { return false; }
        if ($u['cost'] + $costUnit > $lim['cost']) { return false; }
        $u['req'] += 1; $u['cost'] += $costUnit;
        $this->use[$tenantId] = $u;
        return true;
    }
    public function state(string $tenantId): array { return $this->use[$tenantId] ?? ['req'=>0,'cost'=>0.0]; }
}
