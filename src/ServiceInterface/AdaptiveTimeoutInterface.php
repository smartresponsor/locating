<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface AdaptiveTimeoutInterface
{
    public function observe(float $latencyMs): void;
    public function timeout(): int;
}