<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface ProviderGovernanceExecutionStepStatusInterface
{
    public function code(): string;

    public function priority(): string;

    public function ownerHint(): string;

    public function status(): string;

    public function acknowledgementRequired(): bool;

    public function toArray(): array;
}
