<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface ConfidenceEnsembleInterface {
    /**
     * Compute confidence 0..1 combining multiple feature scores:
     * keys may include: 'provider', 'parse', 'reverse', 'distanceKm', 'houseMatch'
     */
    public function score(array $feature): float;
}
