<?php
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface BatchSchedulerInterface
{
    public function add(array $item, int $priority=0, int $deadlineMs=1000): int;
    public function drain(int $n): array;
}