<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface ColdStartWarmupInterface {
    /** Return planned warmup key list for region/op. */
    public function plan(string $region, string $op, int $limit): array;
    /** Execute warmup via resolver (key => array result); return warmed count. */
    public function run(string $region, string $op, array $key, callable $resolver, int $ttlS=300): int;
}
