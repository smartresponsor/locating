<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Observability\Location;

interface LocationMetricsExportServiceInterface
{
    public function exportPrometheus(): string;
}
