<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\RaceExecutor;
final class RaceExecutorTest {
    public function testRace(): void {
        $r = new RaceExecutor();
        $res = $r->race(['p1'=>200,'p2'=>120,'p3'=>-1], 150);
        assert($res['status']==='ok' && $res['provider']==='p2');
    }
}
