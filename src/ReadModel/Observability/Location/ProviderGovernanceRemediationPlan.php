<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRemediationPlanInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRemediationStepInterface;

final class ProviderGovernanceRemediationPlan implements ProviderGovernanceRemediationPlanInterface
{
    public function __construct(
        private readonly string $sourceKey,
        private readonly string $operation,
        private readonly string $severity,
        private readonly string $decision,
        private readonly array $reasons,
        private readonly array $recommendations,
        private readonly array $steps,
    ) {
    }

    public function sourceKey(): string
    {
        return $this->sourceKey;
    }

    public function operation(): string
    {
        return $this->operation;
    }

    public function severity(): string
    {
        return $this->severity;
    }

    public function decision(): string
    {
        return $this->decision;
    }

    public function reasons(): array
    {
        return $this->reasons;
    }

    public function recommendations(): array
    {
        return $this->recommendations;
    }

    public function steps(): array
    {
        return $this->steps;
    }

    public function toArray(): array
    {
        return [
            'sourceKey' => $this->sourceKey,
            'operation' => $this->operation,
            'severity' => $this->severity,
            'decision' => $this->decision,
            'reasons' => $this->reasons,
            'recommendations' => $this->recommendations,
            'steps' => array_map(static fn (ProviderGovernanceRemediationStepInterface $s): array => $s->toArray(), $this->steps),
        ];
    }
}
