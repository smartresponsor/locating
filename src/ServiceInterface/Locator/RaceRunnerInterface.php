<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface RaceRunnerInterface {
    /**
     * Run callable candidates "in parallel" using fibers and return first success.
     * Each callable should throw on failure. Returns ['id'=>string,'value'=>mixed].
     */
    public function run(array $candidate, int $softTimeoutMs=800): array;
}
