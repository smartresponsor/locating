<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface PriorityBatchQueueInterface {
    /** Enqueue with priority (higher first). Return job id. */
    public function enqueue(int $priority, array $payload): string;
    /** Dequeue up to max items ordered by priority. */
    public function dequeueBatch(int $max): array;
    /** Return length of queue. */
    public function length(): int;
}
