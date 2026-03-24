<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface ProviderAuthzPolicyInterface {
    /** Return true if provider is allowed for tenant/region/op. */
    public function allow(string $tenantId, string $region, string $op, string $providerId): bool;
    /** Add rule with priority: action allow|deny, wildcard providerId '*' supported. */
    public function add(string $tenantId, string $region, string $op, string $providerId, string $action, int $priority): void;
}
