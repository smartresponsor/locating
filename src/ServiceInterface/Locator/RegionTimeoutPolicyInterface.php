<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface RegionTimeoutPolicyInterface {
    /** Add rule for provider/region/op timeout ms. Use '*' as wildcard. */
    public function add(string $providerId, string $region, string $op, int $timeoutMs): void;
    /** Resolve timeout for provider/region/op with fallback order: exact -> provider/* -> */op -> default. */
    public function timeout(string $providerId, string $region, string $op, int $defaultMs): int;
}
