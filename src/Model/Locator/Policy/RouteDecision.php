<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Model\Locator\Policy;

final class RouteDecision
{
    /** @param array<string,float|int> $providerScore */
    public function choose(array $providerScore): string
    {
        $best = null;
        $nameEntity = '';
        foreach ($providerScore as $k => $v) {
            if ($best === null || $v > $best) {
                $best = $v;
                $nameEntity = $k;
            }
        }
        return $nameEntity;
    }
}
