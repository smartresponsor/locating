<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Observability\Location;

use App\EntityInterface\Location\LocationStatusReportInterface;

interface LocationStatusReportServiceInterface
{
    public function report(): LocationStatusReportInterface;
}
