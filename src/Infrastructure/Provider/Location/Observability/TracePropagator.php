<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Infrastructure\Provider\Location\Observability;

use App\Locating\InfrastructureInterface\Provider\Location\Observability\TracePropagatorInterface;

final class TracePropagator implements TracePropagatorInterface
{
    /**
     * @param array<string, mixed> $header
     * @return array{trace_id:string,span_id:string}
     */
    public function extract(array $header): array
    {
        $tp = $header['traceparent'] ?? '';
        if (is_string($tp) && '' !== $tp) {
            return ['trace_id' => substr(hash('sha1', $tp), 0, 16), 'span_id' => substr(hash('md5', $tp), 0, 16)];
        }

        return ['trace_id' => bin2hex(random_bytes(8)), 'span_id' => bin2hex(random_bytes(8))];
    }

    /**
     * @param array<string, mixed> $header
     * @param array{trace_id:string,span_id:string} $ctx
     * @return array<string, mixed>
     */
    public function inject(array $header, array $ctx): array
    {
        $trace = $ctx['trace_id'];
        $span = $ctx['span_id'];
        $header['traceparent'] = '00-'.substr($trace, 0, 32).'-'.substr($span, 0, 16).'-01';

        return $header;
    }
}
