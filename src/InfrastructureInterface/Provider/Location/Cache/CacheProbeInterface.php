<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Cache;

interface CacheProbeInterface
{
    /**
     * @param array<string,mixed> $cached
     * @param callable(string):string $checksum
     */
    public function check(string $key, array $cached, callable $checksum): bool;

    /**
     * @param array<string,mixed> $a
     * @param array<string,mixed> $b
     * @return list<string>
     */
    public function diff(array $a, array $b): array;
}
