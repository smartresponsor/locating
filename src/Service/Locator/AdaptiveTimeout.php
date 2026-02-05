<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class AdaptiveTimeout {
    private float $targetP; private int $min; private int $max; private array $buf=[];
    public function __construct(float $targetP=0.95, int $min=100, int $max=2000){
        $this->targetP = max(0.5, min(0.999, $targetP)); $this->min=$min; $this->max=$max;
    }
    public function observe(float $latencyMs): void { $this->buf[] = $latencyMs; if(count($this->buf)>256){ array_shift($this->buf);} }
    public function timeout(): int {
        if (empty($this->buf)) { return $this->min; }
        $tmp = $this->buf; sort($tmp, SORT_NUMERIC);
        $idx = (int)max(0, min(count($tmp)-1, floor($this->targetP * (count($tmp)-1))));
        $t = (int)round($tmp[$idx]);
        return max($this->min, min($this->max, $t));
    }
}
