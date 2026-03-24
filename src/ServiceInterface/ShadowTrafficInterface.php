<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface ShadowTrafficInterface {
    /** Decide shadow provider for request; return '' if no shadow. */
    public function pick(string $primary, array $candidate, float $ratio): string;
    /** Record shadow result for analysis; return noop. */
    public function record(string $primary, string $shadow, array $primaryResult, array $shadowResult): void;
}
