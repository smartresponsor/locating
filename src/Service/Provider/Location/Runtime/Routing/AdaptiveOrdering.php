<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Routing;

use App\Locating\Service\Address\Location\AddressHintBiasService;
use App\Locating\Service\Provider\Location\HealthEwmaService;
use App\Locating\Service\Provider\Location\Runtime\Resilience\SlaPolicy;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Routing\AdaptiveOrderingInterface;

final class AdaptiveOrdering implements AdaptiveOrderingInterface
{
    public function __construct(
        private HealthEwmaService $health,
        private CostAwarePolicy $costPolicy,
        private SlaPolicy $sla,
        private AddressHintBiasService $bias,
    ) {
    }

    public function order(string $region, array $provider, array $signal, array $cost, array $hint): array
    {
        $score = [];
        foreach ($provider as $id) {
            $s = $signal[$id] ?? ['latency_ms' => 250.0, 'error_rate' => 0.02, 'health' => 0.8];
            $h = isset($s['health']) ? (float) $s['health'] : $this->health->health((float) $s['latency_ms'], (float) $s['error_rate']);
            $c = (float) ($cost[$id] ?? 1.0);
            $cw = $this->costPolicy->score($h, $c);
            $sl = $this->sla->weight(300.0, (float) $s['latency_ms'], 0.02, (float) $s['error_rate']);
            $bw = $this->bias->weight($hint, $region);
            $score[$id] = $cw * (0.6 + 0.4 * $sl) * $bw;
        }
        arsort($score, SORT_NUMERIC);

        return array_keys($score);
    }
}
