<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Scoring;

interface ConfidenceEnsembleInterface
{
    /**
     * Compute confidence 0..1 combining multiple feature scores.
     *
     * @param array{provider?:float|int,parse?:float|int,reverse?:float|int,distanceKm?:float|int,houseMatch?:int|bool} $feature
     */
    public function score(array $feature): float;
}
