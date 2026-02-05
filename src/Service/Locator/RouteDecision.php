<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
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
