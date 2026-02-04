<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service\Locator;
final class LatencyForecast {
    private int $cap; private array $buf=[];
    public function __construct(int $cap=128){ $this->cap = max(8, $cap); }
    public function update(float $latencyMs): void {
        $this->buf[] = $latencyMs;
        if (count($this->buf) > $this->cap) { array_shift($this->buf); }
    }
    /** Predict percentile (e.g., 0.95) from recent buffer */
    public function predict(float $p): float {
        if (empty($this->buf)) { return 0.0; }
        $tmp = $this->buf; sort($tmp, SORT_NUMERIC);
        $idx = (int)max(0, min(count($tmp)-1, floor($p * (count($tmp)-1))));
        return (float)$tmp[$idx];
    }
}
