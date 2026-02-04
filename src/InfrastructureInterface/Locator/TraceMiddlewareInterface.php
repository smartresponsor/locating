<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\InfrastructureInterface\Locator;
interface TraceMiddlewareInterface {
    /** Enrich outgoing headers with traceparent; if absent, create one. Returns header map. */
    public function inject(array $header): array;
    /** Extract traceparent from incoming headers; return ['traceId'=>string,'spanId'=>string] */
    public function extract(array $header): array;
}
