<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Resilience;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface CircuitBreakerInterface
{
    public function allow(): bool;
    public function recordSuccess(): void;
    public function recordFailure(): void;
}
