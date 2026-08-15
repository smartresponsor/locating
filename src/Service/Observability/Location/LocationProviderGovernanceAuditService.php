<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAuditEntry;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAuditReport;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceAuditReportInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceAuditServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceRecommendationServiceInterface;

final class LocationProviderGovernanceAuditService implements LocationProviderGovernanceAuditServiceInterface
{
    public function __construct(
        private readonly LocationProviderGovernanceExplanationServiceInterface $explanationService,
        private readonly LocationProviderGovernanceRecommendationServiceInterface $recommendationService,
    ) {
    }

    public function report(): ProviderGovernanceAuditReportInterface
    {
        $explanations = $this->explanationService->report()->providers();
        $recommendations = $this->recommendationService->report()->providers();
        $items = [];

        foreach ($explanations as $sourceKey => $explanation) {
            $providerRecommendations = $recommendations[$sourceKey] ?? null;
            $recommendedActions = $providerRecommendations?->recommendations() ?? [];
            $items[$sourceKey] = new ProviderGovernanceAuditEntry(
                $sourceKey,
                $explanation->operation(),
                $explanation->severity(),
                $explanation->reasons(),
                $recommendedActions,
                $this->decisionFor($explanation->severity(), $recommendedActions),
            );
        }

        return new ProviderGovernanceAuditReport('location', $items);
    }

    /** @param list<string> $recommendations */
    private function decisionFor(string $severity, array $recommendations): string
    {
        return match ($severity) {
            'critical' => 'escalate-governance-review',
            'degraded' => in_array('reduce-traffic-share', $recommendations, true) ? 'deprioritize-provider' : 'review-provider',
            'warning' => 'monitor-provider',
            default => 'keep-provider-enabled',
        };
    }
}
