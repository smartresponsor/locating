<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceReport;
use App\EntityInterface\Location\ProviderGovernanceReportInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceReportServiceInterface;

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
