<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\ScoreEnsemble;
final class ScoreEnsembleTest {
    public function testWeights(): void {
        $e = new ScoreEnsemble();
        $a = $e->score(['text'=>0.9,'geo'=>0.8,'reliability'=>0.6,'history'=>0.5]);
        $e->setWeight(['text'=>0.1,'reliability'=>0.7]);
        $b = $e->score(['text'=>0.9,'geo'=>0.8,'reliability'=>0.6,'history'=>0.5]);
        assert($b > 0 && $a != $b);
    }
}
