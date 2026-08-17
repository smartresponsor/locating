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

    /**
     * @param list<string> $provider
     * @param array<string, array{latency_ms?:float|int, error_rate?:float|int, health?:float|int}> $signal
     * @param array<string, float|int> $cost
     * @param array<string, mixed> $hint
     * @return list<string>
     */
    public function order(string $region, array $provider, array $signal, array $cost, array $hint): array
    {
        $score = [];
        foreach ($provider as $id) {
            $s = $signal[$id] ?? [];
            $latency = (float) ($s['latency_ms'] ?? 250.0);
            $errorRate = (float) ($s['error_rate'] ?? 0.02);
            $health = $s['health'] ?? null;
            $h = null !== $health ? (float) $health : $this->health->health($latency, $errorRate);
            $c = (float) ($cost[$id] ?? 1.0);
            $cw = $this->costPolicy->score($h, $c);
            $sl = $this->sla->weight(300.0, $latency, 0.02, $errorRate);
            $bw = $this->bias->weight($hint, $region);
            $score[$id] = $cw * (0.6 + 0.4 * $sl) * $bw;
        }
        arsort($score, SORT_NUMERIC);

        return array_keys($score);
    }
}
