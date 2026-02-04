<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface RateLimiterRedisInterface
{
    public function allow(string $key, int $tokens=1, int $refillRate=10, int $burst=20): bool;
}