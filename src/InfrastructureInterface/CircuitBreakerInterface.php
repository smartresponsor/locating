<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface;

/**
 */

interface CircuitBreakerInterface
{
    public function allow(): bool;
    public function recordSuccess(): void;
    public function recordFailure(): void;
}