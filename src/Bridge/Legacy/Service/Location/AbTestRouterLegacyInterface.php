<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;

interface AbTestRouterInterface
{
    /** Decide variant ('A' or 'B') for tenant based on ratio (0..1 for B); returns provider id. */
    public function decide(string $tenantId, string $op, array $variant, float $ratioB): string;
}
