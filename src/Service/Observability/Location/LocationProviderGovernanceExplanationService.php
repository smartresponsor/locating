<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExplanation;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExplanationReport;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExplanationReportInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;

final class LocationProviderGovernanceExplanationService implements LocationProviderGovernanceExplanationServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceCatalogServiceInterface $catalogService)
    {
    }

    public function report(): ProviderGovernanceExplanationReportInterface
    {
        $items = [];

        foreach ($this->catalogService->catalog() as $sourceKey => $snapshot) {
            $reasons = [];
            $severity = 'ok';

            if ($snapshot->successRate() < 0.9) {
                $reasons[] = sprintf('success-rate-below-threshold: %.3f < 0.900', $snapshot->successRate());
                $severity = 'degraded';
            }

            if (false === $snapshot->quotaAllowed()) {
                $reasons[] = 'quota-denied';
                $severity = 'critical';
            }

            if ($snapshot->unitCost() > 0.005) {
                $reasons[] = sprintf('unit-cost-high: %.6f > 0.005000', $snapshot->unitCost());
                if ('ok' === $severity) {
                    $severity = 'warning';
                }
            }

            if ($snapshot->ewmaMs() > 500.0) {
                $reasons[] = sprintf('latency-high: %.1fms > 500.0ms', $snapshot->ewmaMs());
                if ('ok' === $severity) {
                    $severity = 'warning';
                }
            }

            if ([] === $reasons) {
                $reasons[] = 'within-governance-policy';
            }

            $items[$sourceKey] = new ProviderGovernanceExplanation(
                $sourceKey,
                $snapshot->operation(),
                $severity,
                $reasons,
            );
        }

        return new ProviderGovernanceExplanationReport('location', $items);
    }
}
