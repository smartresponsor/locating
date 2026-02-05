<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface TimeoutCalibratorInterface {
    /** Calibrate timeout based on baseline, p95 and error rate (ms). */
    public function calibrate(int $baseMs, float $p95Ms, float $errorRate): int;
}
