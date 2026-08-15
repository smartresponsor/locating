<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Infrastructure\Provider\Location\Cache;

use App\Locating\InfrastructureInterface\Provider\Location\Cache\CacheProbeInterface;

final class CacheProbe implements CacheProbeInterface
{
    public function check(string $key, array $cached, callable $checksum): bool
    {
        $base = json_encode($cached, JSON_UNESCAPED_SLASHES);
        $want = hash('sha256', (string) $base);
        $have = (string) $checksum($key);

        return hash_equals($want, $have);
    }

    public function diff(array $a, array $b): array
    {
        $issue = [];
        foreach ($a as $k => $v) {
            if (!array_key_exists($k, $b)) {
                $issue[] = 'missing:'.$k;
            }
        }
        foreach ($b as $k => $v) {
            if (!array_key_exists($k, $a)) {
                $issue[] = 'extra:'.$k;
            }
        }

        return $issue;
    }
}
