<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRemediationStepInterface;

final class ProviderGovernanceRemediationStep implements ProviderGovernanceRemediationStepInterface
{
    public function __construct(
        private readonly string $code,
        private readonly string $priority,
        private readonly string $summary,
        private readonly string $ownerHint,
    ) {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function priority(): string
    {
        return $this->priority;
    }

    public function summary(): string
    {
        return $this->summary;
    }

    public function ownerHint(): string
    {
        return $this->ownerHint;
    }

    /** @return array{code:string,priority:string,summary:string,ownerHint:string} */
    public function toArray(): array
    {
        return ['code' => $this->code, 'priority' => $this->priority, 'summary' => $this->summary, 'ownerHint' => $this->ownerHint];
    }
}
