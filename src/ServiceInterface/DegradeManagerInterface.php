<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface DegradeManagerInterface {
    /** Decide degrade mode for op based on health/error and cache presence. Return string enum. */
    public function decide(string $op, float $health, float $errorRate, bool $hasCache): string;
}
