<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\LayerInterface\Domaine;
interface ResultCacheInterface {
    public function get(string $key): ?array;
    public function put(string $key, array $val, int $ttlS): void;
    public function invalidate(string $key): void;
}
