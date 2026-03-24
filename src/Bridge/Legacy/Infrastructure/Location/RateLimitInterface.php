<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Infrastructure\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp.
 */
interface RateLimitInterface
{
    public function allow(string $bucket): bool;
}
