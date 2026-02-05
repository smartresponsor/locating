<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class TimeoutCalibrator implements TimeoutCalibratorInterface {
    public function __construct(private int $minMs=200, private int $maxMs=3000){}
    public function calibrate(int $baseMs, float $p95Ms, float $errorRate): int {
        $t = max(1,$baseMs);
        $adj = 1.0;
        if ($p95Ms > $t*1.2) { $adj *= 1.15; }
        if ($p95Ms < $t*0.8 && $errorRate < 0.02) { $adj *= 0.9; }
        if ($errorRate > 0.05) { $adj *= 1.2; }
        $out = (int)round($t*$adj);
        return max($this->minMs, min($this->maxMs, $out));
    }
}
