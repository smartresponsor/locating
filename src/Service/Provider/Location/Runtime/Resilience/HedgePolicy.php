<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Resilience;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience\HedgePolicyInterface;

final class HedgePolicy implements HedgePolicyInterface
{
    public function __construct(private float $p = 0.9, private int $min = 50, private int $max = 1500)
    {
    }

    /** @param list<float|int> $latencySample */
    public function delayMs(array $latencySample): int
    {
        if (empty($latencySample)) {
            return -1;
        }
        sort($latencySample, SORT_NUMERIC);
        $idx = (int) max(0, min(count($latencySample) - 1, floor($this->p * (count($latencySample) - 1))));
        $d = (int) round($latencySample[$idx]);

        return max($this->min, min($this->max, $d));
    }
}
