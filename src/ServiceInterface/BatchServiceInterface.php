<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface BatchServiceInterface
{
    public function handle(array $items, int $deadlineMs=1000): array;
}