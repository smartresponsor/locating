<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Cache;

use App\Locating\ServiceInterface\Provider\Location\Cache\CacheProbeInterface;

final class CacheProbe implements CacheProbeInterface
{
    /**
     * @param array<string,mixed> $cached
     * @param callable(string):string $checksum
     */
    public function check(string $key, array $cached, callable $checksum): bool
    {
        $base = json_encode($cached, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $want = hash('sha256', $base);
        $have = $checksum($key);

        return hash_equals($want, $have);
    }

    /**
     * @param array<string,mixed> $a
     * @param array<string,mixed> $b
     * @return list<string>
     */
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
