<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface JitterInterface
{
    public function backoffMs(int $attempt, int $baseMs=50, int $capMs=1000): int;
}