<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface CacheTtlPolicyInterface {
    /** Set default ttl seconds for tag. */
    public function set(string $tag, int $ttlS): void;
    /** Return ttl seconds for key using tag-based policy and fallback. */
    public function ttl(string $key, array $ctx=[]): int;
}
