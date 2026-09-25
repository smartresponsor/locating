<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\PolicyInterface\Provider\Location\Runtime\Experiment\BanditPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderOrderInterface;

final class ProviderOrderService implements ProviderOrderInterface
{
    public function __construct(private ScoreEnsembleService $ensemble, private HealthEwmaService $health)
    {
    }

    /**
     * @param array<string, array{latency_ms?:float|int,error_rate?:float|int,unit_cost?:float|int}> $signal
     * @return list<string>
     */
    public function rank(array $signal, BanditPolicyInterface $bandit): array
    {
        $score = [];
        foreach ($signal as $id => $s) {
            $h = $this->health->health((float) ($s['latency_ms'] ?? 200.0), (float) ($s['error_rate'] ?? 0.05));
            $sig = [
                'text' => 1.0, // Neutral baseline when no text signal is available.
                'geo' => 1.0,
                'reliability' => $h,
                'history' => 0.5,
            ];
            $base = $this->ensemble->score($sig);
            $cost = max(0.0001, (float) ($s['unit_cost'] ?? 1.0));
            // prefer lower cost, higher base
            $score[$id] = $base * (1.0 / (1.0 + $cost));
        }
        arsort($score, SORT_NUMERIC);
        $rank = array_keys($score);
        // nudge top by bandit preference
        if (!empty($rank)) {
            $choice = $bandit->select(array_fill_keys($rank, 1));
            if ('' !== $choice && $rank[0] !== $choice) {
                $idx = array_search($choice, $rank, true);
                if (false !== $idx) {
                    array_splice($rank, $idx, 1);
                    array_unshift($rank, $choice);
                }
            }
        }

        return $rank;
    }
}
