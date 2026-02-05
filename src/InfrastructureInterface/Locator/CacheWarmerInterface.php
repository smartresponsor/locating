<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface CacheWarmerInterface {
    /** Plan warm set for tenant; return list of normalized keys to warm. */
    public function plan(string $tenantId, array $hint): array;
    /** Record warm result. */
    public function record(string $tenantId, string $key, bool $ok, int $latencyMs): void;
}
