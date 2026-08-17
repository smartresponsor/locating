<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Metrics;

interface BudgetTelemetryInterface
{
    /**
     * Render Prometheus exposition for budget remaining and used.
     *
     * @param list<array{tenantId?:string,op?:string,remaining?:float|int,used?:float|int}> $row
     */
    public function export(array $row): string;
}
