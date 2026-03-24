<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\FailoverPlannerLegacyInterface;

final class FailoverPlanner implements FailoverPlannerLegacyInterface
{
    public function __construct(private SlaPolicy $sla)
    {
    }

    public function plan(string $region, array $provider, array $signal): array
    {
        $score = [];
        foreach ($provider as $id) {
            $s = $signal[$id] ?? ['p95_ms' => 300.0, 'error_rate' => 0.02];
            $w = $this->sla->weight(300.0, (float) $s['p95_ms'], 0.02, (float) $s['error_rate']);
            $score[$id] = $w;
        }
        arsort($score, SORT_NUMERIC);

        return array_keys($score);
    }
}
