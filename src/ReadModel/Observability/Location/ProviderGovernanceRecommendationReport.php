<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRecommendationInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRecommendationReportInterface;

final class ProviderGovernanceRecommendationReport implements ProviderGovernanceRecommendationReportInterface
{
    /** @param array<string, ProviderGovernanceRecommendationInterface> $providers */
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

    /** @return array{service:string,itemCount:int,providers:array<string,array{sourceKey:string,operation:string,severity:string,recommendations:list<string>}>} */
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
