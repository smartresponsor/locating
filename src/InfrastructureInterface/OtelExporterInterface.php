<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\InfrastructureInterface;
interface OtelExporterInterface {
    /** Export a span as normalized array (OTLP-like); return JSON string. */
    public function export(array $span): string;
}
