<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\RaceRunner;
final class RaceRunnerTest {
    public function testRun(): void {
        $r = new RaceRunner();
        $winner = $r->run([
            'slow' => function(){ usleep(200_000); return 2; },
            'fast' => function(){ usleep(10_000); return 1; }
        ], 500);
        assert($winner['id']==='fast' && $winner['value']===1);
    }
}
