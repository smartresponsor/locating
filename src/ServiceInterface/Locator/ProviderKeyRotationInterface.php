<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface ProviderKeyRotationInterface {
    /** Return active key version string for provider/tenant/region. */
    public function active(string $providerId, string $tenantId, string $region): string;
    /** Rotate key to next version (returns new active version). */
    public function rotate(string $providerId, string $tenantId, string $region): string;
}
