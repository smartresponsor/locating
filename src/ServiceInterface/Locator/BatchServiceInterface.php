<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface BatchServiceInterface
{
    public function handle(array $items, int $deadlineMs=1000): array;
}