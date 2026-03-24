<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\CircuitTuningPolicyLegacyInterface;

final class CircuitTuningPolicy implements CircuitTuningPolicyLegacyInterface
{
    public function window(float $errorRate, float $p95Ms): int
    {
        $base = 300; // ms
        $penalty = (int) min(3000, $p95Ms / 2 + $errorRate * 2000);

        return max(200, $base + $penalty);
    }

    public function threshold(float $errorRate, float $p95Ms): int
    {
        $t = 3;
        if ($errorRate > 0.2 || $p95Ms > 800) {
            $t = 2;
        }
        if ($errorRate > 0.4 || $p95Ms > 1200) {
            $t = 1;
        }

        return $t;
    }
}
