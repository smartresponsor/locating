<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\DryRunSimulator;
final class DryRunSimulatorTest {
    public function testSim(): void {
        $s = new DryRunSimulator();
        $s->set('t', true);
        $r = $s->simulate('geocode', ['text'=>'1600 Amphitheatre Pkwy']);
        assert(isset($r['lat']) && $r['providerId']==='dry-run');
    }
}
