<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface FraudSignalDetectorInterface {
    /**
     * Return suspicion score 0..1 (1 is highly suspicious).
     * input may contain: ipDistanceKm, velocityRps, failRatio, newDevice(0/1), proxy(0/1)
     */
    public function score(array $signal): float;
}
