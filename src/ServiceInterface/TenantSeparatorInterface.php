<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface TenantSeparatorInterface {
    /** Return sanitized schema name for tenant in Postgres, e.g., 't_acme'. */
    public function schema(string $tenantId): string;
    /** Return namespaced storage key (cache, rate-limit, etc.). */
    public function storageKey(string $tenantId, string $baseKey): string;
}
