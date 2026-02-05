<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class ConfidenceScore {
    /** Compute score in [0..1] from provider signals */
    public function score(array $signal): float {
        $match = (float)($signal['text_match'] ?? 0.0);
        $geo   = (float)($signal['geo_dist_km'] ?? 999.0);
        $prov  = (float)($signal['provider_rank'] ?? 0.0);
        $bias  = (float)($signal['bias_bonus'] ?? 0.0);
        $geoPenalty = max(0.0, 1.0 - min(1.0, $geo / 50.0)); // <=50km ~ ok
        $raw = 0.5*$match + 0.3*$prov + 0.2*$geoPenalty + $bias;
        return max(0.0, min(1.0, $raw));
    }
}
