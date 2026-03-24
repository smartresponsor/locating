<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface HealthEwmaInterface {
    /** observe one call latency and outcome; return new health 0..1 (1 is best) */
    public function observe(float $latencyMs, bool $ok): float;
    /** get current health 0..1 */
    public function health(float $latencyMs, float $errorRate): float;
}
