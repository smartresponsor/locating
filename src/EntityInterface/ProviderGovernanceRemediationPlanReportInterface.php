<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface ProviderGovernanceRemediationPlanReportInterface
{
    public function service(): string;

    public function itemCount(): int;

    public function providers(): array;

    public function toArray(): array;
}
