<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class ProviderStatAggregator implements ProviderStatAggregatorInterface {
    /** @var array<string, array<string, array{n:int, ok:int, ewma:float, cost:float}>> */
    private array $st = [];
    public function __construct(private float $alpha=0.25){}
    public function observe(string $providerId, string $region, float $latencyMs, bool $ok, float $cost): void {
        $p = $providerId; $r = $region;
        $row = $this->st[$p][$r] ?? ['n'=>0,'ok'=>0,'ewma'=>$latencyMs,'cost'=>0.0];
        $row['n'] += 1;
        if ($ok) { $row['ok'] += 1; }
        $row['ewma'] = (1-$this->alpha)*$row['ewma'] + $this->alpha*$latencyMs;
        $row['cost'] = $row['cost'] + $cost;
        $this->st[$p][$r] = $row;
    }
    public function snapshot(): array {
        $out = [];
        foreach ($this->st as $p=>$R){
            foreach ($R as $r=>$row){
                $err = $row['n']>0 ? 1.0 - ($row['ok']/$row['n']) : 0.0;
                $avgCost = $row['n']>0 ? $row['cost']/$row['n'] : 0.0;
                $out[] = ['providerId'=>$p,'region'=>$r,'count'=>$row['n'],'errorRate'=>$err,'p95Ms'=>$row['ewma']*1.6,'costAvg'=>$avgCost];
            }
        }
        return $out;
    }
}
