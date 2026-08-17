<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExecutionItem;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExecutionReport;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExecutionStepStatus;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExecutionItemInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExecutionReportInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRemediationStepInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExecutionServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceRemediationPlanServiceInterface;

final class LocationProviderGovernanceExecutionService implements LocationProviderGovernanceExecutionServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceRemediationPlanServiceInterface $plans)
    {
    }

    public function report(): ProviderGovernanceExecutionReportInterface
    {
        /** @var array<string, ProviderGovernanceExecutionItemInterface> $items */
        $items = [];
        foreach ($this->plans->report()->providers() as $sourceKey => $plan) {
            $steps = [];
            $requiresAck = false;

            foreach ($plan->steps() as $step) {
                $execution = $this->executionStatusFor($step);
                if ($execution->acknowledgementRequired()) {
                    $requiresAck = true;
                }
                $steps[] = $execution;
            }

            $items[$sourceKey] = new ProviderGovernanceExecutionItem(
                $sourceKey,
                $plan->decision(),
                $plan->severity(),
                $requiresAck ? 'pending-acknowledgement' : 'ready',
                $steps,
            );
        }

        return new ProviderGovernanceExecutionReport('location', $items);
    }

    private function executionStatusFor(ProviderGovernanceRemediationStepInterface $step): ProviderGovernanceExecutionStepStatus
    {
        return match ($step->priority()) {
            'high' => new ProviderGovernanceExecutionStepStatus($step->code(), $step->priority(), $step->ownerHint(), 'awaiting-operator-ack', true),
            'medium' => new ProviderGovernanceExecutionStepStatus($step->code(), $step->priority(), $step->ownerHint(), 'planned', false),
            default => new ProviderGovernanceExecutionStepStatus($step->code(), $step->priority(), $step->ownerHint(), 'monitoring', false),
        };
    }
}
