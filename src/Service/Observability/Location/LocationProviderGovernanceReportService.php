<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceReport;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceReportInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceReportServiceInterface;

final class LocationProviderGovernanceReportService implements LocationProviderGovernanceReportServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceCatalogServiceInterface $catalogService)
    {
    }

    public function report(): ProviderGovernanceReportInterface
    {
        return new ProviderGovernanceReport('location', $this->catalogService->catalog());
    }
}
