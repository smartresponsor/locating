<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\TraceMiddleware;
final class TraceMiddlewareTest {
    public function testInjectExtract(): void {
        $m = new TraceMiddleware();
        $h = $m->inject([]);
        $ex = $m->extract($h);
        assert(strlen($ex['traceId'])===32 && strlen($ex['spanId'])===16);
    }
}
