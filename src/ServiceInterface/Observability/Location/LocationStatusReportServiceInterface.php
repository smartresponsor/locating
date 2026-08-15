<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\LocationStatusReportInterface;

interface LocationStatusReportServiceInterface
{
    public function report(): LocationStatusReportInterface;
}
