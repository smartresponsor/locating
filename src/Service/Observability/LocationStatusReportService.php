<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Observability\Location;

use App\Entity\Location\LocationStatusReport;
use App\EntityInterface\Location\LocationStatusReportInterface;
use App\InfrastructureInterface\Provider\Location\ProviderMetricSnapshotStoreInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use App\ServiceInterface\Observability\Location\LocationStatusReportServiceInterface;

final class LocationStatusReportService implements LocationStatusReportServiceInterface
{
    public function __construct(
        private readonly ProviderMetricSnapshotStoreInterface $snapshotStore,
        private readonly LocationProviderGovernanceCatalogServiceInterface $governanceCatalogService,
    ) {
    }

    public function report(): LocationStatusReportInterface
    {
        $metrics = $this->snapshotStore->snapshot();
        $governance = $this->governanceCatalogService->catalog();
        $status = 'ok';

        foreach ($metrics as $snapshot) {
            if ($snapshot->errorRate() > 0.005) {
                $status = 'degraded';
                break;
            }
        }

        foreach ($governance as $signal) {
            if ($signal->successRate() < 0.9 || false === $signal->quotaAllowed()) {
                $status = 'degraded';
                break;
            }
        }

        return new LocationStatusReport('location', $status, $metrics, $governance);
    }
}
