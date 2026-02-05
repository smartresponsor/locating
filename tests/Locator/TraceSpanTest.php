<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Http\Locator\TraceMiddleware;
final class TraceSpanTest {
    public function testWrap(): void {
        $m = new TraceMiddleware();
        $fn = function(array $q, $ctx){ return ['ok'=>true]; };
        [$res, $span] = $m->handle($fn, ['q'=>'x']);
        assert($res['ok']===true && isset($span['dur_ms']));
    }
}
