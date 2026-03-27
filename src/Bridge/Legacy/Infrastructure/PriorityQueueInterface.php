<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Infrastructure\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp.
 */
interface PriorityQueueInterface
{
    public function push(mixed $value, int $priority = 0): void;

    public function pop(): mixed;

    public function len(): int;
}
