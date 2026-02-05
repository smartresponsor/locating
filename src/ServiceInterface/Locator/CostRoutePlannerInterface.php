<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface CostRoutePlannerInterface {
    /**
     * Plan provider order based on unit cost and signal map.
     * $signal[id] => ['latencyMs'=>float,'errorRate'=>float]
     * Return ordered id list best-first (lower cost, better signal).
     */
    public function order(array $provider, array $unitCost, array $signal): array;
}
