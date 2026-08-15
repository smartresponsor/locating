<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceMetricSetInterface
{
    public function service(): string;

    /**
     * @return list<string>
     */
    public function lines(): array;

    public function toPrometheus(): string;
}
