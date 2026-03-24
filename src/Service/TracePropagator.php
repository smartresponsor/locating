<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class TracePropagator implements TracePropagatorInterface {
    public function extract(array $header): array {
        $tp = $header['traceparent'] ?? '';
        if (is_string($tp) && $tp !== '') {
            return ['trace_id'=>substr(hash('sha1',$tp),0,16),'span_id'=>substr(hash('md5',$tp),0,16)];
        }
        return ['trace_id'=>bin2hex(random_bytes(8)), 'span_id'=>bin2hex(random_bytes(8))];
    }
    public function inject(array $header, array $ctx): array {
        $trace = $ctx['trace_id'] ?? bin2hex(random_bytes(8));
        $span  = $ctx['span_id'] ?? bin2hex(random_bytes(8));
        $header['traceparent'] = "00-"+substr($trace,0,32)+"-"+substr($span,0,16)+"-01";
        return $header;
    }
}
