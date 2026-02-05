<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface CanaryToggleInterface {
    /** Return true if flag is enabled for a tenant/user bucket. */
    public function isEnabled(string $flag, string $tenantId, ?string $userId=null): bool;
    /** Enable flag for percent of users (0..100). */
    public function enablePercent(string $flag, string $tenantId, int $percent): void;
    /** Disable flag for tenant. */
    public function disable(string $flag, string $tenantId): void;
}
