<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class AdaptiveTimeoutCalibrator implements AdaptiveTimeoutCalibratorInterface {
    public function __construct(private int $minMs=200, private int $maxMs=2000){}
    public function calibrate(float $p95Ms, float $errorRate, int $currentMs): int {
        $t = (int)round($p95Ms * (1.1 + min(0.5, $errorRate*0.8)));
        $t = max($this->minMs, min($this->maxMs, $t));
        // damp change to avoid oscillation
        $alpha = 0.3;
        $res = (int)round((1-$alpha)*$currentMs + $alpha*$t);
        return max($this->minMs, min($this->maxMs, $res));
    }
}
