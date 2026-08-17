<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Security;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Security\FraudSignalDetectorInterface;

final class FraudSignalDetector implements FraudSignalDetectorInterface
{
    /** @param array{ipDistanceKm?:float|int,velocityRps?:float|int,failRatio?:float|int,newDevice?:int|bool,proxy?:int|bool} $signal */
    public function score(array $signal): float
    {
        $ip = (float)($signal['ipDistanceKm'] ?? 0.0);      // > 2000km spikes suspicion
        $vel = (float)($signal['velocityRps'] ?? 0.0);      // > 5 rps spikes suspicion
        $fail = (float)($signal['failRatio'] ?? 0.0);       // 0..1
        $new = (int)($signal['newDevice'] ?? 0);
        $px  = (int)($signal['proxy'] ?? 0);
        $s = 0.0;
        $s += min(1.0, $ip / 5000.0) * 0.3;
        $s += min(1.0, $vel / 5.0)   * 0.25;
        $s += min(1.0, $fail)      * 0.25;
        $s += ($new ? 1.0 : 0.0)       * 0.1;
        $s += ($px ? 1.0 : 0.0)        * 0.1;
        return max(0.0, min(1.0, $s));
    }
}
