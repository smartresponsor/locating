<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Http;
use App\Layer\{TraceContext, Span};
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
