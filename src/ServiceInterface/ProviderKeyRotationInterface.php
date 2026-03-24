<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface ProviderKeyRotationInterface {
    /** Return active key version string for provider/tenant/region. */
    public function active(string $providerId, string $tenantId, string $region): string;
    /** Rotate key to next version (returns new active version). */
    public function rotate(string $providerId, string $tenantId, string $region): string;
}
