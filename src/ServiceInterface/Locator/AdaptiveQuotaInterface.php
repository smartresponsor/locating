<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface AdaptiveQuotaInterface {
    /** Compute new quota (req_limit, cost_limit) given usage and error rate. */
    public function compute(string $tenantId, int $reqUsed, float $costUsed, int $reqLimit, float $costLimit, float $errorRate): array;
}
