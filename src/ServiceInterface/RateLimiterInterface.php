<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface RateLimiterInterface {
    /** Return true if operation may proceed; tokens is amount requested, burst is max in window. */
    public function allow(string $key, int $tokens=1, int $refillRate=10, int $burst=20): bool;
}
