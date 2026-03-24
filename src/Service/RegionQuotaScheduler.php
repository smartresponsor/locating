<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class RegionQuotaScheduler implements RegionQuotaSchedulerInterface {
    /** @var array<string, array{cap:int, used:int}> */
    private array $q = [];
    public function split(int $total, array $weight): array {
        $total = max(0,$total);
        $sum = 0.0; foreach ($weight as $w){ $sum += max(0.0,(float)$w); }
        if ($sum <= 0.0) { $this->q = []; return []; }
        $remain = $total; $alloc = [];
        // First pass: floor allocation
        foreach ($weight as $r=>$w){
            $cap = (int)floor($total * (max(0.0,(float)$w)/$sum));
            $alloc[(string)$r] = $cap; $remain -= $cap;
        }
        // Distribute remainder by largest fractional
        $frac = [];
        foreach ($weight as $r=>$w){
            $exact = $total*(max(0.0,(float)$w)/$sum);
            $frac[(string)$r] = $exact - floor($exact);
        }
        arsort($frac, \SORT_NUMERIC);
        foreach (array_keys($frac) as $r){
            if ($remain<=0) break;
            $alloc[$r] += 1; $remain -= 1;
        }
        // Store
        $this->q = [];
        foreach ($alloc as $r=>$cap){ $this->q[$r] = ['cap'=>max(0,$cap),'used'=>0]; }
        return $alloc;
    }
    public function consume(string $region): bool {
        $r = $this->q[$region] ?? ['cap'=>0,'used'=>0];
        if ($r['used'] >= $r['cap']) { return false; }
        $r['used'] += 1; $this->q[$region] = $r; return true;
    }
    public function reset(): void { $this->q = []; }
}
