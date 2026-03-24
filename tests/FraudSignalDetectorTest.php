<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\FraudSignalDetector;
final class FraudSignalDetectorTest {
    public function testScore(): void {
        $d = new FraudSignalDetector();
        $s1 = $d->score(['ipDistanceKm'=>100,'velocityRps'=>0.5,'failRatio'=>0.01,'newDevice'=>0,'proxy'=>0]);
        $s2 = $d->score(['ipDistanceKm'=>6000,'velocityRps'=>10,'failRatio'=>0.8,'newDevice'=>1,'proxy'=>1]);
        assert($s2 > $s1 && $s2 <= 1.0);
    }
}
