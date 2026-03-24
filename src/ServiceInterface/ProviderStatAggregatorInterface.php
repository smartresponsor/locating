<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface ProviderStatAggregatorInterface {
    /** Observe single call outcome for provider/region. */
    public function observe(string $providerId, string $region, float $latencyMs, bool $ok, float $cost): void;
    /** Return snapshot per provider/region: count, errorRate, p95Ms (EWMA approx), costAvg. */
    public function snapshot(): array;
}
