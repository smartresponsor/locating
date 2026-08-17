<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Routing;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Routing\RegionRouterInterface;

final class RegionRouter implements RegionRouterInterface
{
    /**
     * @param array<string,float|int> $regionHealth
     * @param array<string,float|int> $slaWeight
     */
    public function select(array $regionHealth, array $slaWeight): string
    {
        $best = '';
        $score = -1.0;
        foreach ($regionHealth as $r => $h) {
            $w = (float) ($slaWeight[$r] ?? 1.0);
            $s = $h * $w;
            if ($s > $score) {
                $score = $s;
                $best = $r;
            }
        }

        return $best;
    }
}
