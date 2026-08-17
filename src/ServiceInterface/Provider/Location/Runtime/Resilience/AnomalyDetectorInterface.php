<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface AnomalyDetectorInterface
{
    /**
     * Return true if last value is anomaly based on z-score threshold and change ratio.
     *
     * @param list<float|int> $series
     */
    public function detect(array $series, float $z, float $ratio): bool;
}
