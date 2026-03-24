<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface RateLimiterRedisInterface
{
    public function allow(string $key, int $tokens=1, int $refillRate=10, int $burst=20): bool;
}