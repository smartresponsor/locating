<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface ProbabilisticFailoverInterface {
    /** Return true if should failover to next provider based on health and jitter. */
    public function should(float $health, float $errorRate): bool;
}
