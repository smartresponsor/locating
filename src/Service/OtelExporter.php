<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class OtelExporter implements OtelExporterInterface {
    public function export(array $span): string {
        $out = [
            'traceId' => (string)($span['traceId'] ?? bin2hex(random_bytes(16))),
            'spanId'  => (string)($span['spanId'] ?? bin2hex(random_bytes(8))),
            'name'    => (string)($span['name'] ?? 'locator.op'),
            'start'   => (int)($span['start'] ?? (int)(microtime(true)*1e6)),
            'end'     => (int)($span['end'] ?? (int)(microtime(true)*1e6)),
            'attr'    => (array)($span['attr'] ?? []),
            'status'  => (string)($span['status'] ?? 'OK'),
        ];
        return json_encode($out, JSON_UNESCAPED_SLASHES);
    }
}
