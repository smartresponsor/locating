<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class TraceMiddleware implements TraceMiddlewareInterface {
    public function inject(array $header): array {
        $h = $header;
        if (!isset($h['traceparent'])) {
            $traceId = bin2hex(random_bytes(16));
            $spanId  = bin2hex(random_bytes(8));
            $h['traceparent'] = '00-' . $traceId . '-' . $spanId . '-01';
        }
        return $h;
    }
    public function extract(array $header): array {
        $tp = (string)($header['traceparent'] ?? '');
        if (\preg_match('/^[0-9a-f]{2}-([0-9a-f]{32})-([0-9a-f]{16})-[0-9a-f]{2}$/i', $tp, $m)) {
            return ['traceId'=>strtolower($m[1]), 'spanId'=>strtolower($m[2])];
        }
        return ['traceId'=>'', 'spanId'=>''];
    }
}
