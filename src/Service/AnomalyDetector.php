<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class AnomalyDetector implements AnomalyDetectorInterface {
    public function detect(array $series, float $z, float $ratio): bool {
        $n = count($series);
        if ($n < 6) { return false; }
        $last = (float)$series[$n-1];
        $base = array_slice($series, 0, $n-1);
        $m = array_sum($base)/max(1,count($base));
        $sd = 0.0;
        foreach ($base as $v){ $sd += ($v-$m)*($v-$m); }
        $sd = sqrt($sd/max(1,count($base)));
        $zv = ($sd>0)? abs(($last-$m)/$sd) : 0.0;
        $prev = (float)$series[$n-2];
        $chg = ($prev!=0.0)? abs(($last-$prev)/$prev) : 0.0;
        return ($zv >= max(1.0,$z)) or ($chg >= max(0.0,$ratio));
    }
}
