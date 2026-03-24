<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\ServiceInterface;
interface FailoverPlannerInterface {
    /**
     * Build failover chain for region given providers and signals.
     * @param array $provider list of provider ids
     * @param array $signal map id => ['p95_ms'=>float,'error_rate'=>float]
     * @return array ordered provider ids (best first)
     */
    public function plan(string $region, array $provider, array $signal): array;
}
