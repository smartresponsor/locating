<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceExecutionItem;
use App\Entity\Location\ProviderGovernanceExecutionReport;
use App\Entity\Location\ProviderGovernanceExecutionStepStatus;
use App\EntityInterface\Location\ProviderGovernanceExecutionReportInterface;
use App\EntityInterface\Location\ProviderGovernanceRemediationStepInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceExecutionServiceInterface;
use App\ServiceInterface\Observability\Location\ProviderGovernanceRemediationPlanServiceInterface;

final class ProviderGovernanceExecutionService implements ProviderGovernanceExecutionServiceInterface
{
    public function __construct(private readonly ProviderGovernanceRemediationPlanServiceInterface $plans)
    {
    }

    public function report(): ProviderGovernanceExecutionReportInterface
    {
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
