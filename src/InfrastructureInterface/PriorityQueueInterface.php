<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface;

/**
 */

interface PriorityQueueInterface
{
    public function push(mixed $value, int $priority=0): void;
    public function pop(): mixed;
    public function len(): int;
}