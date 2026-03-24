<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceReport;
use App\EntityInterface\Location\ProviderGovernanceReportInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceCatalogServiceInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceReportServiceInterface;

final class ProviderGovernanceReportService implements ProviderGovernanceReportServiceInterface
{
    public function __construct(private readonly ProviderGovernanceCatalogServiceInterface $catalogService)
    {
    }

    public function report(): ProviderGovernanceReportInterface
    {
        return new ProviderGovernanceReport('location', $this->catalogService->catalog());
    }
}
