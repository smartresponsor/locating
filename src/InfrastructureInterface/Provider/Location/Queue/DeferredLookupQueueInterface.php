<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Queue;

interface DeferredLookupQueueInterface
{
    /** @param array<string,mixed> $payload */
    public function enqueue(string $tenantId, array $payload): string;

    /** @return array{id:string,tenant:string,payload:array<string,mixed>}|null */
    public function dequeue(): ?array;
}
