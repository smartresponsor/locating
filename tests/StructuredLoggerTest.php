<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\StructuredLogger;
final class StructuredLoggerTest {
    public function testEmit(): void {
        $l = new StructuredLogger();
        $s = $l->emit('batch', ['count'=>2,'tenant'=>'t']);
        $obj = json_decode($s, true);
        assert($obj['name']==='batch' && $obj['count']===2 && $obj['tenant']==='t');
    }
}
