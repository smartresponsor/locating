<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\ServiceInterface;

/**
 */

interface StrategyRegistryInterface
{
    public function add(object $p): void;
    public function byName(string $name): ?object;
    public function names(): array;
}