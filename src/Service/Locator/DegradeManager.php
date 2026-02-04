<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service\Locator;
final class DegradeManager implements DegradeManagerInterface {
    public function decide(string $op, float $health, float $errorRate, bool $hasCache): string {
        $h = max(0.0, min(1.0,$health));
        $e = max(0.0, min(1.0,$errorRate));
        if ($h >= 0.8 && $e <= 0.05) { return 'none'; }
        if ($hasCache) {
            if ($e <= 0.15) { return 'cache-read'; }
            return 'cache-stale';
        }
        if ($op === 'reverse') { return 'reduced-precision'; }
        return 'offline-accept';
    }
}
