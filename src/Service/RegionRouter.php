<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class RegionRouter {
    /** Select region by health and SLA weight (simplified) */
    public function select(array $regionHealth, array $slaWeight): string {
        $best=''; $score=-1.0;
        foreach($regionHealth as $r=>$h){
            $w = (float)($slaWeight[$r] ?? 1.0);
            $s = $h * $w;
            if ($s > $score){ $score=$s; $best=$r; }
        }
        return $best;
    }
}
