<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\ProviderGovernanceMetricSetInterface;

final class ProviderGovernanceMetricSet implements ProviderGovernanceMetricSetInterface
{
    /**
     * @param list<string> $lines
     */
    public function __construct(
        private readonly string $service,
        private readonly array $lines,
    ) {
    }

    public function service(): string
    {
        return $this->service;
    }

    public function lines(): array
    {
        return $this->lines;
    }

    public function toPrometheus(): string
    {
        return implode("\n", $this->lines)."\n";
    }
}
