<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface IdempotencyKeyStoreInterface {
    /** Accept key for window ttlS. Return false if duplicate inside window. */
    public function accept(string $key, int $ttlS): bool;
    /** Purge expired keys. */
    public function purge(): void;
}
