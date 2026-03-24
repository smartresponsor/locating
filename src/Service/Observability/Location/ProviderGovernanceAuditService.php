<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceAuditEntry;
use App\Entity\Location\ProviderGovernanceAuditReport;
use App\EntityInterface\Location\ProviderGovernanceAuditReportInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceAuditServiceInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceExplanationServiceInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceRecommendationServiceInterface;

final class ProviderGovernanceAuditService implements ProviderGovernanceAuditServiceInterface
{
    public function __construct(
        private readonly ProviderGovernanceExplanationServiceInterface $explanationService,
        private readonly ProviderGovernanceRecommendationServiceInterface $recommendationService,
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
