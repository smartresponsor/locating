<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface LatencyForecastInterface {
    /** Observe a new latency sample (ms) for provider/region. */
    public function observe(string $providerId, string $region, float $latencyMs): void;
    /** Return forecast p (e.g., expected next latency) for provider/region. */
    public function forecast(string $providerId, string $region): float;
}
