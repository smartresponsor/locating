<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Security;

interface FraudSignalDetectorInterface
{
    /**
     * Return suspicion score 0..1 (1 is highly suspicious).
     *
     * @param array{ipDistanceKm?:float|int,velocityRps?:float|int,failRatio?:float|int,newDevice?:int|bool,proxy?:int|bool} $signal
     */
    public function score(array $signal): float;
}
