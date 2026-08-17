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
    /** @param array<string,float|int> $signal */
    public function score(array $signal): float;

    /** @param array<string,float|int> $w */
    public function setWeight(array $w): void;
}
