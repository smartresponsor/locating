<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Batch;

final class BatchScheduler
{
    private PriorityQueue $queue;
    public function __construct(?PriorityQueue $queue = null)
    {
        $this->queue = $queue ?? new PriorityQueue();
    }
    /** Accept item with priority and deadline; return count in queue */
    public function add(array $item, int $priority = 0, int $deadlineMs = 1000): int
    {
        $this->queue->push(['item' => $item, 'deadline_ms' => $deadlineMs], $priority);
        return $this->queue->len();
    }
    /** Drain up to n items */
    public function drain(int $n): array
    {
        $out = [];
        for ($i = 0;$i < $n;$i++) {
            $v = $this->queue->pop();
            if ($v === null) {
                break;
            } $out[] = $v;
        }
        return $out;
    }
}
