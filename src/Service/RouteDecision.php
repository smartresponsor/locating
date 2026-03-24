<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class RouteDecision {
    /** Choose provider with highest health */
    public function choose(array $providerScore): string {
        $best = null; $name = '';
        foreach($providerScore as $k=>$v){
            if ($best===null || $v>$best){ $best=$v; $name=$k; }
        }
        return $name;
    }
}
