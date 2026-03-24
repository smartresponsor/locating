<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface RateLimiterMemoryInterface
{
    public function allow(string $key, int $limit, int $windowMs): bool;
}