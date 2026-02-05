<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\ServiceInterface\Locator;
interface HealthRecorderInterface {
    public function ok(string $key, float $latencyMs): void;
    public function fail(string $key, float $latencyMs): void;
    /** Return snapshot: ['ok'=>int,'fail'=>int,'avg_ms'=>float,'error_rate'=>float] */
    public function snapshot(string $key): array;
}
