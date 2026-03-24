<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class ProbabilisticFailover implements ProbabilisticFailoverInterface {
    public function __construct(private float $base=0.05){}
    public function should(float $health, float $errorRate): bool {
        $h = max(0.0, min(1.0, $health));
        $e = max(0.0, min(1.0, $errorRate));
        $p = $this->base + (1.0 - $h)*0.4 + $e*0.4; // 0.05..0.85
        $r = random_int(0, 1000) / 1000.0;
        return $r < min(0.95, max(0.0, $p));
    }
}
