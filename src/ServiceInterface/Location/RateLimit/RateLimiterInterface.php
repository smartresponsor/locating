<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Location\RateLimit;

interface RateLimiterInterface
{
    /** Return true if operation may proceed; tokens is amount requested, burst is max in window. */
    public function allow(string $key, int $tokens = 1, int $refillRate = 10, int $burst = 20): bool;
}
