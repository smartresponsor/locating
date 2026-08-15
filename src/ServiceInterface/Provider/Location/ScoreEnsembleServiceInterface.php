<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location;

interface ScoreEnsembleServiceInterface
{
    /** Return final score 0..1 based on multiple signals (0..1). */
    public function score(array $signal): float;
    /** Configure weights for signals. */
    public function setWeight(array $w): void;
}
