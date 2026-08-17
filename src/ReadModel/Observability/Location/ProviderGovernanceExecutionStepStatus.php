<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExecutionStepStatusInterface;

final class ProviderGovernanceExecutionStepStatus implements ProviderGovernanceExecutionStepStatusInterface
{
    public function __construct(
        private readonly string $code,
        private readonly string $priority,
        private readonly string $ownerHint,
        private readonly string $status,
        private readonly bool $acknowledgementRequired,
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

    public function ownerHint(): string
    {
        return $this->ownerHint;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function acknowledgementRequired(): bool
    {
        return $this->acknowledgementRequired;
    }

    /** @return array{code:string,priority:string,ownerHint:string,status:string,acknowledgementRequired:bool} */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'priority' => $this->priority,
            'ownerHint' => $this->ownerHint,
            'status' => $this->status,
            'acknowledgementRequired' => $this->acknowledgementRequired,
        ];
    }
}
