<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface IpAuthzPolicyInterface {
    /** Return true if ip is allowed for tenant. */
    public function allow(string $tenantId, string $ip): bool;
    /** Add rule with priority: action 'allow'|'deny', cidr e.g. '192.168.0.0/16' */
    public function add(string $tenantId, string $action, string $cidr, int $priority): void;
}
