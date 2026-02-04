<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface RaceExecutorInterface {
    /** Run N providers "in parallel" (simulated) and return first success result. */
    public function race(array $candidate, int $timeoutMs): array;
}
