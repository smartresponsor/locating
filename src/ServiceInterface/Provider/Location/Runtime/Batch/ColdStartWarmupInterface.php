<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Batch;

interface ColdStartWarmupInterface
{
    /** @return list<string> */
    public function plan(string $region, string $op, int $limit): array;

    /**
     * @param list<string> $key
     * @param callable(string):array<string,mixed> $resolver
     */
    public function run(string $region, string $op, array $key, callable $resolver, int $ttlS = 300): int;
}
