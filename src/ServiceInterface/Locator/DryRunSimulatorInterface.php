<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface DryRunSimulatorInterface {
    /** Enable or disable dry-run for tenant. */
    public function set(string $tenantId, bool $enabled): void;
    /** Simulate geocode/other op returning plausible payload. */
    public function simulate(string $op, array $input): array;
    /** Return true if tenant is in dry-run. */
    public function enabled(string $tenantId): bool;
}
