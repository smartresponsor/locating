<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\LogEvent;
use App\Layer\Locator\Redactor;
final class LogEventTest {
    public function testRedaction(): void {
        $l = new LogEvent(new Redactor());
        $j = $l->toJson(['email'=>'a@b.c','x'=>1],'t','s');
        assert(strpos($j,'<redacted>') !== false && strpos($j,'"x":1') !== false);
    }
}
