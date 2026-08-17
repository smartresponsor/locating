<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Cache;

interface ResultCacheInterface
{
    /** @return array<string, mixed>|null */
    public function get(string $key): ?array;

    /** @param array<string, mixed> $val */
    public function put(string $key, array $val, int $ttlS): void;

    public function invalidate(string $key): void;
}
