<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class ProviderOrder implements ProviderOrderInterface {
    public function __construct(private ScoreEnsemble $ensemble, private HealthEwma $health) {}
    public function rank(array $signal, BanditPolicyInterface $bandit): array {
        $score = [];
        foreach ($signal as $id=>$s) {
            $h = $this->health->health((float)($s['latency_ms']??200.0), (float)($s['error_rate']??0.05));
            $sig = [
                'text' => 1.0, // placeholder for NLP signal if any
                'geo' => 1.0,
                'reliability' => $h,
                'history' => 0.5,
            ];
            $base = $this->ensemble->score($sig);
            $cost = max(0.0001, (float)($s['unit_cost'] ?? 1.0));
            // prefer lower cost, higher base
            $score[$id] = $base * (1.0/(1.0+$cost));
        }
        arsort($score, SORT_NUMERIC);
        $rank = array_keys($score);
        // nudge top by bandit preference
        if (!empty($rank)) {
            $choice = $bandit->select(array_fill_keys($rank, 1));
            if ($choice !== '' && $rank[0] !== $choice) {
                $idx = array_search($choice, $rank, true);
                if ($idx !== false) { array_splice($rank, $idx, 1); array_unshift($rank, $choice); }
            }
        }
        return $rank;
    }
}
