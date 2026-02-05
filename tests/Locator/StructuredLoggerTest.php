<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\StructuredLogger;
final class StructuredLoggerTest {
    public function testEmit(): void {
        $l = new StructuredLogger();
        $s = $l->emit('batch', ['count'=>2,'tenant'=>'t']);
        $obj = json_decode($s, true);
        assert($obj['name']==='batch' && $obj['count']===2 && $obj['tenant']==='t');
    }
}
