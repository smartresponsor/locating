<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class ScoreEnsemble implements ScoreEnsembleInterface {
    /** @var array<string,float> */
    private array $w = ['text'=>0.4,'geo'=>0.2,'reliability'=>0.3,'history'=>0.1];
    public function setWeight(array $w): void {
        foreach($w as $k=>$v){ $this->w[$k] = (float)$v; }
    }
    public function score(array $signal): float {
        $s = 0.0;
        foreach($this->w as $k=>$w){
            $v = max(0.0, min(1.0, (float)($signal[$k] ?? 0.0)));
            $s += $w * $v;
        }
        return max(0.0, min(1.0, $s));
    }
}
