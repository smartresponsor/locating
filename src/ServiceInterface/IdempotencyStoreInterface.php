<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface IdempotencyStoreInterface {
    /** Return true if key was stored now (first time), false if already exists and is valid. */
    public function checkAndPut(string $key, int $ttlS=300): bool;
}
