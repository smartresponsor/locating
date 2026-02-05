<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\ConfidenceScore;
final class ConfidenceScoreTest {
    public function testScoreRange(): void {
        $s = new ConfidenceScore();
        $v = $s->score(['text_match'=>0.8,'geo_dist_km'=>10,'provider_rank'=>0.6]);
        assert($v >= 0.0 && $v <= 1.0);
    }
}
