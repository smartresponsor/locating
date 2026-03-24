<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\RaceExecutorLegacyInterface;

final class RaceExecutor implements RaceExecutorLegacyInterface
{
    /**
     * @param array $candidate map providerId => simulated latency ms; negative = failure
     *
     * @return array ['provider'=>string,'latency_ms'=>float,'status'=>'ok'|'error']
     */
    public function race(array $candidate, int $timeoutMs): array
    {
        $bestProv = '';
        $bestLat = $timeoutMs + 1;
        foreach ($candidate as $id => $lat) {
            $l = (float) $lat;
            if ($l < 0) {
                continue;
            }
            if ($l <= $timeoutMs && $l < $bestLat) {
                $bestLat = $l;
                $bestProv = (string) $id;
            }
        }
        if ('' === $bestProv) {
            return ['status' => 'error', 'error' => 'timeout'];
        }

        return ['status' => 'ok', 'provider' => $bestProv, 'latency_ms' => $bestLat];
    }
}
