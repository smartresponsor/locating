<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\ConfidenceEnsemble;
final class ConfidenceEnsembleTest {
    public function testScore(): void {
        $e = new ConfidenceEnsemble();
        $low = $e->score(['provider'=>0.3,'parse'=>0.3,'reverse'=>0.3,'distanceKm'=>5]);
        $high = $e->score(['provider'=>0.9,'parse'=>0.9,'reverse'=>0.9,'distanceKm'=>0.05,'houseMatch'=>1]);
        assert($high > $low && $high <= 1.0);
    }
}
