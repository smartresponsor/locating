<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Entity\Locator;
final class TenantGuard {
    /** Ensure resource tenant matches context; return true if allowed */
    public function allow(TenantContextInterface $ctx, string $resourceTenant): bool {
        return $ctx->id() === $resourceTenant;
    }
}
