<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class LogEvent {
    public function __construct(private Redactor $redactor = new Redactor()) {}
    public function toJson(array $event, string $traceId='', string $spanId=''): string {
        $event['_trace_id'] = $traceId;
        $event['_span_id'] = $spanId;
        $safe = $this->redactor->apply($event);
        return json_encode($safe, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
