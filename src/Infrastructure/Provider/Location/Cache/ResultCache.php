<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Infrastructure\Provider\Location\Cache;

use App\Locating\InfrastructureInterface\Provider\Location\Cache\ResultCacheInterface;

final class ResultCache implements ResultCacheInterface
{
    /** @var array<string, array{val:array, exp:int}> */
    private array $map = [];

    public function get(string $key): ?array
    {
        $now = time();
        $row = $this->map[$key] ?? null;
        if (null === $row) {
            return null;
        }
        if ($row['exp'] > 0 && $now >= $row['exp']) {
            unset($this->map[$key]);

            return null;
        }

        return $row['val'];
    }

    public function put(string $key, array $val, int $ttlS): void
    {
        $this->map[$key] = ['val' => $val, 'exp' => time() + max(1, $ttlS)];
    }

    public function invalidate(string $key): void
    {
        unset($this->map[$key]);
    }
}
