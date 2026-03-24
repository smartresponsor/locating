<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class BanditRouter implements BanditRouterInterface {
    /** @var array<string, array<string, array<string, array{n:int,avg:float}>>> region=>op=>provider=>stats */
    private array $st = [];
    public function select(string $region, string $op, array $provider): string {
        $t = &$this->st[$region][$op];
        $N = 0; foreach ($provider as $id){ $row = $t[$id] ?? ['n'=>0,'avg'=>0.0]; $t[$id]=$row; $N += $row['n']; }
        $c = 2.0; // exploration factor
        $bestId = (string)$provider[0]; $bestU = -1.0;
        foreach ($provider as $id){
            $row = $t[$id];
            $n = max(0, (int)$row['n']); $avg = (float)$row['avg'];
            $u = ($n>0 ? $avg + \sqrt(($c*\log(max(1,$N)))/$n) : 1.0); // optimistic init
            if ($u > $bestU){ $bestU=$u; $bestId=(string)$id; }
        }
        return $bestId;
    }
    public function update(string $region, string $op, string $providerId, float $reward): void {
        $t = &$this->st[$region][$op][$providerId];
        if (!isset($t)){ $t = ['n'=>0,'avg'=>0.0]; }
        $n = $t['n'] + 1;
        $t['avg'] = ($t['avg'] * $t['n'] + max(0.0,min(1.0,$reward))) / $n;
        $t['n'] = $n;
    }
}
