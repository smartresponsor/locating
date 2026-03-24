<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class AdaptiveQuota implements AdaptiveQuotaInterface {
    /** Simple controller: if errorRate high -> tighten; if low and budget left -> loosen slightly. */
    public function compute(string $tenantId, int $reqUsed, float $costUsed, int $reqLimit, float $costLimit, float $errorRate): array {
        $leftReq = max(0, $reqLimit - $reqUsed);
        $leftCost = max(0.0, $costLimit - $costUsed);
        $reqAdj = 0; $costAdj = 0.0;
        if ($errorRate > 0.05) { $reqAdj = (int)max(-floor($reqLimit*0.2), -1000); $costAdj = -min($costLimit*0.2, 100.0); }
        elseif ($leftReq > $reqLimit*0.3 && $leftCost > $costLimit*0.3 && $errorRate < 0.01) {
            $reqAdj = (int)min(floor($reqLimit*0.1), 500); $costAdj = min($costLimit*0.1, 50.0);
        }
        return ['req_limit'=>max(1, $reqLimit+$reqAdj), 'cost_limit'=>max(0.01, $costLimit+$costAdj)];
    }
}
