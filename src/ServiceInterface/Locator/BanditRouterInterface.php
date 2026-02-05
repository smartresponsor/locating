<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface BanditRouterInterface {
    /** Select arm (provider id) to use for region/op. */
    public function select(string $region, string $op, array $provider): string;
    /** Update reward for provider after call: reward in 0..1 (e.g., 1 for success, 0 for fail, or soft via latency). */
    public function update(string $region, string $op, string $providerId, float $reward): void;
}
