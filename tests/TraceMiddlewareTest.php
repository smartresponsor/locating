<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\TraceMiddleware;
final class TraceMiddlewareTest {
    public function testInjectExtract(): void {
        $m = new TraceMiddleware();
        $h = $m->inject([]);
        $ex = $m->extract($h);
        assert(strlen($ex['traceId'])===32 && strlen($ex['spanId'])===16);
    }
}
