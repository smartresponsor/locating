<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Observability\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Store\ProviderMetricSnapshotStoreInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationMetricsExportServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;

final class LocationMetricsExportService implements LocationMetricsExportServiceInterface
{
    public function __construct(
        private readonly ProviderMetricSnapshotStoreInterface $snapshotStore,
        private readonly LocationProviderGovernanceCatalogServiceInterface $governanceCatalogService,
    ) {
    }

    public function exportPrometheus(): string
    {
        $lines = [
            '# HELP locator_request_total Total Locator requests per operation.',
            '# TYPE locator_request_total counter',
            '# HELP locator_request_error_total Total Locator failed requests per operation.',
            '# TYPE locator_request_error_total counter',
            '# HELP locator_request_latency_avg_ms Average Locator latency in milliseconds per operation.',
            '# TYPE locator_request_latency_avg_ms gauge',
            '# HELP locator_request_error_rate Locator error rate per operation.',
            '# TYPE locator_request_error_rate gauge',
            '# HELP locator_provider_success_rate Locator provider success rate by source.',
            '# TYPE locator_provider_success_rate gauge',
            '# HELP locator_provider_quota_allowed Locator provider quota availability by source.',
            '# TYPE locator_provider_quota_allowed gauge',
            '# HELP locator_provider_unit_cost Locator provider unit cost by source.',
            '# TYPE locator_provider_unit_cost gauge',
        ];

        foreach ($this->snapshotStore->snapshot() as $snapshot) {
            $lines[] = sprintf('locator_request_total{operation="%s"} %d', $snapshot->operation(), $snapshot->count());
            $lines[] = sprintf('locator_request_error_total{operation="%s"} %d', $snapshot->operation(), $snapshot->errorCount());
            $lines[] = sprintf('locator_request_latency_avg_ms{operation="%s"} %.3f', $snapshot->operation(), $snapshot->avgMs());
            $lines[] = sprintf('locator_request_error_rate{operation="%s"} %.5f', $snapshot->operation(), $snapshot->errorRate());
        }

        foreach ($this->governanceCatalogService->catalog() as $signal) {
            $lines[] = sprintf('locator_provider_success_rate{source="%s",operation="%s"} %.5f', $signal->sourceKey(), $signal->operation(), $signal->successRate());
            $lines[] = sprintf('locator_provider_quota_allowed{source="%s",operation="%s"} %d', $signal->sourceKey(), $signal->operation(), $signal->quotaAllowed() ? 1 : 0);
            $lines[] = sprintf('locator_provider_unit_cost{source="%s",operation="%s"} %.5f', $signal->sourceKey(), $signal->operation(), $signal->unitCost());
        }

        return implode("\n", $lines)."\n";
    }
}
