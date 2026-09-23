<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Observability\Location;

interface TracePropagatorInterface
{
    /**
     * Extract TraceContext from headers; create new if missing.
     *
     * @param array<string, mixed> $header
     * @return array{trace_id:string,span_id:string}
     */
    public function extract(array $header): array;

    /**
     * Inject trace headers into outgoing request headers.
     *
     * @param array<string, mixed> $header
     * @param array{trace_id:string,span_id:string} $ctx
     * @return array<string, mixed>
     */
    public function inject(array $header, array $ctx): array;
}
