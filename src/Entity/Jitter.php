<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Entity;
final class Jitter {
    public function backoffMs(int $attempt, int $baseMs=50, int $capMs=1000): int {
        $exp = min($capMs, $baseMs * (1 << min(10, $attempt)));
        $j = random_int(0, $exp);
        return $j;
    }
}
