<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class HealthEwma implements HealthEwmaInterface {
    private float $alpha;
    private float $mLatency=200.0; // ms exponential average
    private float $mError=0.01;    // error rate exponential average
    public function __construct(float $alpha=0.2){ $this->alpha = max(0.01, min(1.0, $alpha)); }
    public function observe(float $latencyMs, bool $ok): float {
        $a = $this->alpha;
        $this->mLatency = (1-$a)*$this->mLatency + $a*max(1.0,$latencyMs);
        $err = $ok ? 0.0 : 1.0;
        $this->mError   = (1-$a)*$this->mError + $a*$err;
        return $this->score();
    }
    public function health(float $latencyMs, float $errorRate): float {
        // combine fresh sample with EWMA memo
        $lat = 0.5*$this->mLatency + 0.5*max(1.0,$latencyMs);
        $err = 0.5*$this->mError + 0.5*max(0.0,min(1.0,$errorRate));
        $latNorm = 1.0/(1.0 + ($lat/200.0));        // lower latency -> closer to 1
        $errNorm = 1.0 - $err;                      // lower error -> closer to 1
        return max(0.0, min(1.0, 0.6*$latNorm + 0.4*$errNorm));
    }
    private function score(): float {
        $latNorm = 1.0/(1.0 + ($this->mLatency/200.0));
        $errNorm = 1.0 - $this->mError;
        return max(0.0, min(1.0, 0.6*$latNorm + 0.4*$errNorm));
    }
}
