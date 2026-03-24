<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface ScoreEnsembleInterface {
    /** Return final score 0..1 based on multiple signals (0..1). */
    public function score(array $signal): float;
    /** Configure weights for signals. */
    public function setWeight(array $w): void;
}
