<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface RegionQuotaSchedulerInterface {
    /** Compute per-region quota split given total and weight (region=>weight). */
    public function split(int $total, array $weight): array;
    /** Consume one unit in region if available; return true on success. */
    public function consume(string $region): bool;
    /** Reset internal counters (e.g., new window). */
    public function reset(): void;
}
