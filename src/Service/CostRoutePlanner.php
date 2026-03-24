<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class CostRoutePlanner implements CostRoutePlannerInterface {
    public function order(array $provider, array $unitCost, array $signal): array {
        $score = [];
        foreach ($provider as $id){
            $lat = (float)($signal[$id]['latencyMs'] ?? 300.0);
            $err = (float)($signal[$id]['errorRate'] ?? 0.02);
            $cost = (float)($unitCost[$id] ?? 1.0);
            // lower is better: convert to utility
            $util = 1.0/(1.0 + $cost) * 1.0/(1.0 + $lat/300.0) * (1.0 - min(0.9,$err));
            $score[(string)$id] = $util;
        }
        arsort($score, SORT_NUMERIC);
        return array_keys($score);
    }
}
