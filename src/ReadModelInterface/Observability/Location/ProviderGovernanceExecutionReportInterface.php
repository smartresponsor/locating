<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceExecutionReportInterface
{
    public function service(): string;

    public function itemCount(): int;

    public function providers(): array;

    public function toArray(): array;
}
