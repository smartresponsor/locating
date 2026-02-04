<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface EnvInterface
{
    public function get(string $key, string $default = ''): string;
}