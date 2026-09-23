<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Cache;

interface CacheTtlPolicyInterface
{
    /** Set default ttl seconds for tag. */
    public function set(string $tag, int $ttlS): void;

    /** @param array<string,mixed> $ctx */
    public function ttl(string $key, array $ctx = []): int;
}
