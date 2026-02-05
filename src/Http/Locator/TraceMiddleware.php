<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Http\Locator;
use Smartresponsor\Layer\Locator\{TraceContext, Span};
final class TraceMiddleware {
    /** Wrap handler call and return tuple: [response, spanData] */
    public function handle(callable $handler, array $query): array {
        $ctx = TraceContext::new();
        $span = new Span('http.locator', $ctx);
        $res = $handler($query, $ctx);
        $span->end();
        return [$res, $span->export(['route'=>'/locator','method'=>'GET'])];
    }
}
