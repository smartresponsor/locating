<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface IdempotencyKeyStoreInterface {
    /** Accept key for window ttlS. Return false if duplicate inside window. */
    public function accept(string $key, int $ttlS): bool;
    /** Purge expired keys. */
    public function purge(): void;
}
