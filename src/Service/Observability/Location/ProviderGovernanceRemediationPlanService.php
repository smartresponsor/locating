<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceRemediationPlan;
use App\Entity\Location\ProviderGovernanceRemediationPlanReport;
use App\Entity\Location\ProviderGovernanceRemediationStep;
use App\EntityInterface\Location\ProviderGovernanceRemediationPlanReportInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceAuditServiceInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceRemediationPlanServiceInterface;

final class ProviderGovernanceRemediationPlanService implements ProviderGovernanceRemediationPlanServiceInterface
{
    public function __construct(private readonly ProviderGovernanceAuditServiceInterface $auditService)
    {
    }

    public function report(): ProviderGovernanceRemediationPlanReportInterface
    {
        $items = [];
        foreach ($this->auditService->report()->providers() as $sourceKey => $audit) {
            $steps = [];
            foreach ($audit->recommendations() as $recommendation) {
                $steps[] = $this->stepFor($recommendation);
            }
            if ([] === $steps) {
                $steps[] = new ProviderGovernanceRemediationStep('keep-provider-enabled', 'low', 'No corrective change required; keep current routing policy.', 'operator');
            }
            $items[$sourceKey] = new ProviderGovernanceRemediationPlan(
                $sourceKey,
                $audit->operation(),
                $audit->severity(),
                $audit->decision(),
                $audit->reasons(),
                $audit->recommendations(),
                $steps,
            );
        }

        return new ProviderGovernanceRemediationPlanReport('location', $items);
    }

    private function stepFor(string $recommendation): ProviderGovernanceRemediationStep
    {
        return match ($recommendation) {
            'reduce-traffic-share' => new ProviderGovernanceRemediationStep($recommendation, 'high', 'Reduce traffic share for the provider on latency-sensitive and realtime paths.', 'routing-policy'),
            'investigate-provider-failures' => new ProviderGovernanceRemediationStep($recommendation, 'high', 'Inspect recent provider failures and correlate with upstream incident windows.', 'operations'),
            'reroute-to-available-provider' => new ProviderGovernanceRemediationStep($recommendation, 'high', 'Route new requests toward available providers while the quota-denied source is constrained.', 'routing-policy'),
            'increase-provider-quota' => new ProviderGovernanceRemediationStep($recommendation, 'medium', 'Review quota limits and raise allowance when business policy permits.', 'governance'),
            'lower-provider-priority' => new ProviderGovernanceRemediationStep($recommendation, 'medium', 'Lower provider priority in ordering so lower-cost providers win tie-breaks.', 'routing-policy'),
            'review-provider-budget' => new ProviderGovernanceRemediationStep($recommendation, 'medium', 'Review provider budget envelope and confirm whether current unit cost is acceptable.', 'finance'),
            'deprioritize-provider-for-realtime-paths' => new ProviderGovernanceRemediationStep($recommendation, 'medium', 'Keep provider enabled for batch or fallback traffic, but deprioritize on realtime request paths.', 'routing-policy'),
            'investigate-provider-latency' => new ProviderGovernanceRemediationStep($recommendation, 'medium', 'Investigate latency spikes, timeout patterns, and regional degradation.', 'operations'),
            default => new ProviderGovernanceRemediationStep($recommendation, 'low', 'Keep provider enabled and continue monitoring governance signals.', 'operator'),
        };
    }
}
