<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceMetricSet;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceMetricSetInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceMetricsExportServiceInterface;

final class LocationProviderGovernanceMetricsExportService implements LocationProviderGovernanceMetricsExportServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceCatalogServiceInterface $catalogService)
    {
    }

    public function export(): ProviderGovernanceMetricSetInterface
    {
        $lines = [
            '# HELP locator_provider_success_rate Locator provider success rate by source.',
            '# TYPE locator_provider_success_rate gauge',
            '# HELP locator_provider_quota_allowed Locator provider quota availability by source.',
            '# TYPE locator_provider_quota_allowed gauge',
            '# HELP locator_provider_unit_cost Locator provider unit cost by source.',
            '# TYPE locator_provider_unit_cost gauge',
            '# HELP locator_provider_degraded Locator provider degraded state by source.',
            '# TYPE locator_provider_degraded gauge',
        ];

        foreach ($this->catalogService->catalog() as $snapshot) {
            $degraded = ($snapshot->successRate() < 0.9 || false === $snapshot->quotaAllowed()) ? 1 : 0;
            $lines[] = sprintf('locator_provider_success_rate{source="%s",operation="%s"} %.5f', $snapshot->sourceKey(), $snapshot->operation(), $snapshot->successRate());
            $lines[] = sprintf('locator_provider_quota_allowed{source="%s",operation="%s"} %d', $snapshot->sourceKey(), $snapshot->operation(), $snapshot->quotaAllowed() ? 1 : 0);
            $lines[] = sprintf('locator_provider_unit_cost{source="%s",operation="%s"} %.5f', $snapshot->sourceKey(), $snapshot->operation(), $snapshot->unitCost());
            $lines[] = sprintf('locator_provider_degraded{source="%s",operation="%s"} %d', $snapshot->sourceKey(), $snapshot->operation(), $degraded);
        }

        return new ProviderGovernanceMetricSet('location', $lines);
    }
}
