<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\AdaptiveProviderOrderLegacyInterface;

final class AdaptiveProviderOrder implements AdaptiveProviderOrderLegacyInterface
{
    public function rank(array $signal): array
    {
        $score = [];
        foreach ($signal as $id => $s) {
            $p95 = (float) ($s['p95Ms'] ?? 400.0);
            $err = (float) ($s['errorRate'] ?? 0.02);
            $cost = (float) ($s['costAvg'] ?? 1.0);
            $score[(string) $id] = 1.0 / (1.0 + $p95 / 300.0) * (1.0 - min(0.9, $err)) * 1.0 / (1.0 + $cost);
        }
        arsort($score, \SORT_NUMERIC);

        return array_keys($score);
    }
}
