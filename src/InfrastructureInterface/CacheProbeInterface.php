<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface CacheProbeInterface {
    /** Return true if cached payload is consistent against checksum provider. */
    public function check(string $key, array $cached, callable $checksum): bool;
    /** Diff two payloads shallowly. */
    public function diff(array $a, array $b): array;
}
