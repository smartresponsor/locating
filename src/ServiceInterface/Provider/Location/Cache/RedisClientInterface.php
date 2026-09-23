<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Cache;

interface RedisClientInterface
{
    public function incrby(string $key, int $n): int;

    public function get(string $key): ?string;

    public function expire(string $key, int $ttl): void;

    public function pttl(string $key): int;

    public function del(string $key): void;
}
