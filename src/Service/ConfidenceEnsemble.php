<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class ConfidenceEnsemble implements ConfidenceEnsembleInterface {
    public function __construct(
        private float $wProvider=0.35,
        private float $wParse=0.25,
        private float $wReverse=0.2,
        private float $wGeo=0.2
    ) {}
    public function score(array $feature): float {
        $p = max(0.0, min(1.0, (float)($feature['provider'] ?? 0.7)));
        $pa = max(0.0, min(1.0, (float)($feature['parse'] ?? 0.6)));
        $rv = max(0.0, min(1.0, (float)($feature['reverse'] ?? 0.6)));
        $distKm = max(0.0, (float)($feature['distanceKm'] ?? 0.0));
        $house = (int)($feature['houseMatch'] ?? 0);
        $geo = max(0.0, min(1.0, 1.0/(1.0 + $distKm/0.5))) * ( $house ? 1.1 : 1.0 );
        $lin = $this->wProvider*$p + $this->wParse*$pa + $this->wReverse*$rv + $this->wGeo*$geo;
        $z = 1.0/(1.0 + exp(-4.0*($lin - 0.5))); // logistic calibration
        return max(0.0, min(1.0, $z));
    }
}
