<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Entity;
final class TenantGuard {
    /** Ensure resource tenant matches context; return true if allowed */
    public function allow(TenantContextInterface $ctx, string $resourceTenant): bool {
        return $ctx->id() === $resourceTenant;
    }
}
