<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;

interface AdaptiveTimeoutCalibratorInterface
{
    /** Calibrate timeout based on observed p95 and error rate; returns new timeout ms. */
    public function calibrate(float $p95Ms, float $errorRate, int $currentMs): int;
}
