<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\InfrastructureInterface\Locator;
interface OtelExporterInterface {
    /** Export a span as normalized array (OTLP-like); return JSON string. */
    public function export(array $span): string;
}
