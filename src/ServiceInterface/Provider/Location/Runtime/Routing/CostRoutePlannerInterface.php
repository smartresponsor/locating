<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface CostRoutePlannerInterface
{
    /**
     * @param list<string> $provider
     * @param array<string, float|int> $unitCost
     * @param array<string, array{latencyMs?:float|int, errorRate?:float|int}> $signal
     * @return list<string>
     */
    public function order(array $provider, array $unitCost, array $signal): array;
}
