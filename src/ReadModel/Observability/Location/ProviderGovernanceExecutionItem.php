<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExecutionItemInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExecutionStepStatusInterface;

final class ProviderGovernanceExecutionItem implements ProviderGovernanceExecutionItemInterface
{
    public function __construct(
        private readonly string $sourceKey,
        private readonly string $decision,
        private readonly string $severity,
        private readonly string $acknowledgementState,
        private readonly array $steps,
    ) {
    }

    public function sourceKey(): string
    {
        return $this->sourceKey;
    }

    public function decision(): string
    {
        return $this->decision;
    }

    public function severity(): string
    {
        return $this->severity;
    }

    public function acknowledgementState(): string
    {
        return $this->acknowledgementState;
    }

    public function steps(): array
    {
        return $this->steps;
    }

    public function toArray(): array
    {
        return [
            'sourceKey' => $this->sourceKey,
            'decision' => $this->decision,
            'severity' => $this->severity,
            'acknowledgementState' => $this->acknowledgementState,
            'steps' => array_map(static fn (ProviderGovernanceExecutionStepStatusInterface $step): array => $step->toArray(), $this->steps),
        ];
    }
}
