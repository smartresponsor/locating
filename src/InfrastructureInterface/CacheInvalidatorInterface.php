<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface CacheInvalidatorInterface {
    /** Register key index entry for potential future pattern invalidation. */
    public function index(string $key): void;
    /** Invalidate exact key. */
    public function key(string $key): int;
    /** Invalidate by prefix pattern; return number of keys planned for purge. */
    public function prefix(string $prefix): int;
}
