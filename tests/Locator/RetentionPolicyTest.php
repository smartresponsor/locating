<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\{RetentionPolicy, RetentionSweeper};
final class RetentionPolicyTest {
    public function testPlan(): void {
        $p = new RetentionPolicy(100);
        $p->set('telemetry', 7);
        $sw = new RetentionSweeper();
        $sql = $sw->plan(['telemetry'=>'locator_telemetry_event','trace'=>'locator_trace_span'], $p);
        assert(count($sql)===2 && str_contains($sql[0], "INTERVAL '7 day'"));
    }
}
