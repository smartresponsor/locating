<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface TenantResourceQuotaInterface {
    /** Configure quota per tenant per resource (e.g., 'geocode','reverse','batch'). */
    public function set(string $tenantId, string $resource, int $reqLimit, float $costLimit=0.0): void;
    /** Charge one op with optional unit cost; return false if limit exceeded. */
    public function charge(string $tenantId, string $resource, float $costUnit=0.0): bool;
    /** Return current usage state. */
    public function state(string $tenantId, string $resource): array;
}
