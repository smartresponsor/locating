<?php
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface PriorityQueueInterface
{
    public function push(mixed $value, int $priority=0): void;
    public function pop(): mixed;
    public function len(): int;
}