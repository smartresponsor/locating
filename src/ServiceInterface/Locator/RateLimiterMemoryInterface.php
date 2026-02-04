<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface RateLimiterMemoryInterface
{
    public function allow(string $key, int $limit, int $windowMs): bool;
}