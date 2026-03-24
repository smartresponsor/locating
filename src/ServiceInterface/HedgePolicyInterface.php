<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface HedgePolicyInterface {
    /** Return ms to send hedge after (percentile-based); negative means do not hedge. */
    public function delayMs(array $latencySample): int;
}
