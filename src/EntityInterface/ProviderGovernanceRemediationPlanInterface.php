<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface ProviderGovernanceRemediationPlanInterface
{
    public function sourceKey(): string;

    public function operation(): string;

    public function severity(): string;

    public function decision(): string;

    public function reasons(): array;

    public function recommendations(): array;

    public function steps(): array;

    public function toArray(): array;
}
