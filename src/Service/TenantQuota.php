<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class TenantQuota implements TenantQuotaInterface {
    /** @var array<string, array<string, array{base:int, adaptive:int, used:int, ewmaError:float}>> */
    private array $map = [];
    public function __construct(private float $alpha=0.3, private float $floorRatio=0.5, private float $ceilRatio=1.5){}
    public function setBase(string $tenantId, string $op, int $base): void {
        $t = &$this->map[$tenantId][$op];
        if (!isset($t)) { $t = ['base'=>max(1,$base),'adaptive'=>max(1,$base),'used'=>0,'ewmaError'=>0.0]; }
        else { $t['base']=max(1,$base); $t['adaptive']=max(1,$base); }
    }
    public function limit(string $tenantId, string $op): int {
        $t = $this->map[$tenantId][$op] ?? ['base'=>100,'adaptive'=>100,'used'=>0,'ewmaError'=>0.0];
        return max(1, (int)$t['adaptive']);
    }
    public function update(string $tenantId, string $op, int $used, float $errorRate): int {
        $t = &$this->map[$tenantId][$op];
        if (!isset($t)) { $this->setBase($tenantId,$op,100); $t = &$this->map[$tenantId][$op]; }
        $t['used'] = max(0,$used);
        $t['ewmaError'] = (1-$this->alpha)*$t['ewmaError'] + $this->alpha*max(0.0,min(1.0,$errorRate));
        $load = ($t['used']+1.0) / max(1.0, (float)$t['adaptive']);
        $factor = 1.0;
        if ($t['ewmaError'] > 0.1) { $factor *= 0.85; }
        if ($load > 0.9) { $factor *= 0.9; }
        if ($load < 0.6 && $t['ewmaError'] < 0.05) { $factor *= 1.15; }
        $base = (float)$t['base'];
        $new = (int)round(min($this->ceilRatio*$base, max($this->floorRatio*$base, $t['adaptive'] * $factor)));
        $t['adaptive'] = max(1, $new);
        return $t['adaptive'];
    }
}
