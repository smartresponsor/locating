<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface HedgerInterface {
    /**
     * Return millisecond offsets for hedged attempt schedule given base and p95.
     * Example: baseMs=50, p95Ms=800 => [0, 150, 350, 700]
     */
    public function offsets(int $baseMs, int $p95Ms): array;
}
