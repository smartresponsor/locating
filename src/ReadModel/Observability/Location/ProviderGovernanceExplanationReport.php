<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExplanationInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExplanationReportInterface;

final class ProviderGovernanceExplanationReport implements ProviderGovernanceExplanationReportInterface
{
    /** @param array<string, ProviderGovernanceExplanationInterface> $providers */
    public function __construct(
        private readonly string $service,
        private readonly array $providers,
    ) {
    }

    public function service(): string
    {
        return $this->service;
    }

    public function itemCount(): int
    {
        return count($this->providers);
    }

    public function providers(): array
    {
        return $this->providers;
    }

    public function toArray(): array
    {
        $providers = [];
        foreach ($this->providers as $sourceKey => $provider) {
            $providers[$sourceKey] = $provider->toArray();
        }

        return [
            'service' => $this->service,
            'itemCount' => $this->itemCount(),
            'providers' => $providers,
        ];
    }
}
