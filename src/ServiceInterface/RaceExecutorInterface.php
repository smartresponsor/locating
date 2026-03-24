<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface RaceExecutorInterface {
    /** Run N providers "in parallel" (simulated) and return first success result. */
    public function race(array $candidate, int $timeoutMs): array;
}
