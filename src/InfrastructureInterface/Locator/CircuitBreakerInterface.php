<?php
declare(strict_types=1);

namespace App\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface CircuitBreakerInterface
{
    public function allow(): bool;
    public function recordSuccess(): void;
    public function recordFailure(): void;
}