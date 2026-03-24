<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface TracePropagatorInterface {
    /** Extract TraceContext from headers; create new if missing. */
    public function extract(array $header): array;
    /** Inject trace headers into outgoing request headers. */
    public function inject(array $header, array $ctx): array;
}
