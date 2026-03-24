<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface CostRoutePlannerInterface {
    /**
     * Plan provider order based on unit cost and signal map.
     * $signal[id] => ['latencyMs'=>float,'errorRate'=>float]
     * Return ordered id list best-first (lower cost, better signal).
     */
    public function order(array $provider, array $unitCost, array $signal): array;
}
