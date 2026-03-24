<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\ServiceInterface;
interface SlaPolicyInterface {
    /** Return weight 0..1 for provider based on target and observed metrics. */
    public function weight(float $p95MsTarget, float $p95MsObserved, float $errorTarget, float $errorObserved): float;
}
