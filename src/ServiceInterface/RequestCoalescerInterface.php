<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface RequestCoalescerInterface {
    /** Return cached/inflight result or register a new inflight by key. */
    public function acquire(string $key): ?array;
    /** Complete inflight with result and cache it. */
    public function release(string $key, array $result, int $ttlS=30): void;
}
