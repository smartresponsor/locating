<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Policy\Provider\Location\Cache;

use App\Locating\PolicyInterface\Provider\Location\Cache\CacheTtlPolicyInterface;

final class CacheTtlPolicy implements CacheTtlPolicyInterface
{
    /** @var array<string,int> */
    private array $map = [];

    public function __construct(private int $fallback = 300)
    {
    }

    public function set(string $tag, int $ttlS): void
    {
        $this->map[$tag] = max(1, $ttlS);
    }

    /** @param array<string,mixed> $ctx */
    public function ttl(string $key, array $ctx = []): int
    {
        $defaultTag = str_starts_with($key, 'addr:') ? 'addr' : 'default';
        $tag = is_string($ctx['tag'] ?? null) && '' !== $ctx['tag'] ? $ctx['tag'] : $defaultTag;

        return $this->map[$tag] ?? $this->fallback;
    }
}
