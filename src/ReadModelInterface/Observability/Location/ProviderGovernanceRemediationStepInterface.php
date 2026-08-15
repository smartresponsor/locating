<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceRemediationStepInterface
{
    public function code(): string;

    public function priority(): string;

    public function summary(): string;

    public function ownerHint(): string;

    public function toArray(): array;
}
