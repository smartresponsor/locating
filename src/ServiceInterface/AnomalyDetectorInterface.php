<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface AnomalyDetectorInterface {
    /** Return true if last value is anomaly based on z-score threshold and change ratio. */
    public function detect(array $series, float $z, float $ratio): bool;
}
