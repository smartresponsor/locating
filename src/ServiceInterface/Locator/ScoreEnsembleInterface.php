<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface ScoreEnsembleInterface {
    /** Return final score 0..1 based on multiple signals (0..1). */
    public function score(array $signal): float;
    /** Configure weights for signals. */
    public function setWeight(array $w): void;
}
