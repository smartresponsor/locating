<?php
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface AdaptiveTimeoutInterface
{
    public function observe(float $latencyMs): void;
    public function timeout(): int;
}