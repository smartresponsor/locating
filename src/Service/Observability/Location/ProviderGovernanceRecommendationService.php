<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceRecommendation;
use App\Entity\Location\ProviderGovernanceRecommendationReport;
use App\EntityInterface\Location\ProviderGovernanceRecommendationReportInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceExplanationServiceInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceRecommendationServiceInterface;

final class ProviderGovernanceRecommendationService implements ProviderGovernanceRecommendationServiceInterface
{
    public function __construct(private readonly ProviderGovernanceExplanationServiceInterface $explanationService)
    {
    }

    public function report(): ProviderGovernanceRecommendationReportInterface
    {
        $items = [];

        foreach ($this->explanationService->report()->providers() as $sourceKey => $provider) {
            $recommendations = [];

            foreach ($provider->reasons() as $reason) {
                if (str_starts_with($reason, 'success-rate-below-threshold')) {
                    $recommendations[] = 'reduce-traffic-share';
                    $recommendations[] = 'investigate-provider-failures';
                    continue;
                }

                if ('quota-denied' === $reason) {
                    $recommendations[] = 'reroute-to-available-provider';
                    $recommendations[] = 'increase-provider-quota';
                    continue;
                }

                if (str_starts_with($reason, 'unit-cost-high')) {
                    $recommendations[] = 'lower-provider-priority';
                    $recommendations[] = 'review-provider-budget';
                    continue;
                }

                if (str_starts_with($reason, 'latency-high')) {
                    $recommendations[] = 'deprioritize-provider-for-realtime-paths';
                    $recommendations[] = 'investigate-provider-latency';
                    continue;
                }
            }

            if ([] === $recommendations) {
                $recommendations[] = 'keep-provider-enabled';
            }

            $items[$sourceKey] = new ProviderGovernanceRecommendation(
                $sourceKey,
                $provider->operation(),
                $provider->severity(),
                array_values(array_unique($recommendations)),
            );
        }

        return new ProviderGovernanceRecommendationReport('location', $items);
    }
}
