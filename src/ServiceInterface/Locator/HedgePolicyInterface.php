<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface HedgePolicyInterface {
    /** Return ms to send hedge after (percentile-based); negative means do not hedge. */
    public function delayMs(array $latencySample): int;
}
