<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface RaceRunnerInterface {
    /**
     * Run callable candidates "in parallel" using fibers and return first success.
     * Each callable should throw on failure. Returns ['id'=>string,'value'=>mixed].
     */
    public function run(array $candidate, int $softTimeoutMs=800): array;
}
