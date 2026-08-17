<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Resilience;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience\HedgerInterface;

final class Hedger implements HedgerInterface
{
    /** @return list<int> */
    public function offsets(int $baseMs, int $p95Ms): array
    {
        $b = max(10, $baseMs);
        $p = max($b * 2, $p95Ms);
        // Progressive schedule with mild jitter
        $seq = [$b, (int) ($b * 3), (int) ($b * 6), (int) ($p * 0.9)];
        $out = [0];
        foreach ($seq as $t) {
            $j = random_int(-10, 10);
            $out[] = max($b, $t + $j);
        }
        // ensure strictly increasing and <= p95
        $final = [];
        $last = -1;
        foreach ($out as $x) {
            if ($x <= $p && $x > $last) {
                $final[] = $x;
                $last = $x;
            }
        }

        return $final;
    }
}
