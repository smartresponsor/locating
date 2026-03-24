<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface CircuitTuningPolicyInterface {
    /** Decide open/half-open window (ms) based on error rate/latency. */
    public function window(float $errorRate, float $p95Ms): int;
    /** Decide failure threshold (count) before open. */
    public function threshold(float $errorRate, float $p95Ms): int;
}
