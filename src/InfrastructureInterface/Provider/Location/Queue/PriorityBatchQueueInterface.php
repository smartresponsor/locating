<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Queue;

interface PriorityBatchQueueInterface
{
    /** @param array<string,mixed> $payload */
    public function enqueue(int $priority, array $payload): string;

    /** @return list<array{id:string,payload:array<string,mixed>}> */
    public function dequeueBatch(int $max): array;

    /** Return length of queue. */
    public function length(): int;
}
