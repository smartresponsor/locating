<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface RetryPolicyInterface {
    /** Return delay ms for attempt index (0-based). 'full' jitter means random in [0, backoffMs]. */
    public function delay(int $attempt, int $baseMs=50, int $factor=2, int $maxMs=2000, string $jitter='full'): int;
}
