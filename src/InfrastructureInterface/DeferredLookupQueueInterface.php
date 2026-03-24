<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface DeferredLookupQueueInterface {
    /** Enqueue deferred lookup and return job id. */
    public function enqueue(string $tenantId, array $payload): string;
    /** Dequeue next job (if any). */
    public function dequeue(): ?array;
}
