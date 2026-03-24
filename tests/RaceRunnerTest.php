<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\RaceRunner;
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
