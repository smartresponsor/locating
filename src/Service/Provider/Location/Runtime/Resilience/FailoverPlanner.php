<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Resilience;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience\FailoverPlannerInterface;

final class FailoverPlanner implements FailoverPlannerInterface
{
    public function __construct(private SlaPolicy $sla)
    {
    }

    /**
     * @param list<string> $provider
     * @param array<string, array{p95_ms?:float|int,error_rate?:float|int}> $signal
     * @return list<string>
     */
    public function plan(string $region, array $provider, array $signal): array
    {
        $score = [];
        foreach ($provider as $id) {
            $s = $signal[$id] ?? [];
            $p95 = (float) ($s['p95_ms'] ?? 300.0);
            $errorRate = (float) ($s['error_rate'] ?? 0.02);
            $w = $this->sla->weight(300.0, $p95, 0.02, $errorRate);
            $score[$id] = $w;
        }
        arsort($score, SORT_NUMERIC);

        return array_keys($score);
    }
}
