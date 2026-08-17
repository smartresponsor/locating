<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRemediationPlanInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceRemediationPlanReportInterface;

final class ProviderGovernanceRemediationPlanReport implements ProviderGovernanceRemediationPlanReportInterface
{
    /** @param array<string, ProviderGovernanceRemediationPlanInterface> $providers */
    public function __construct(private readonly string $service, private readonly array $providers)
    {
    }

    public function service(): string
    {
        return $this->service;
    }

    public function itemCount(): int
    {
        return count($this->providers);
    }

    /** @return array<string, ProviderGovernanceRemediationPlanInterface> */
    public function providers(): array
    {
        return $this->providers;
    }

    /** @return array{service:string,itemCount:int,providers:array<string,array{sourceKey:string,operation:string,severity:string,decision:string,reasons:list<string>,recommendations:list<string>,steps:list<array{code:string,priority:string,summary:string,ownerHint:string}>}>} */
    public function toArray(): array
    {
        $providers = [];
        foreach ($this->providers as $sourceKey => $provider) {
            $providers[$sourceKey] = $provider->toArray();
        }

        return ['service' => $this->service, 'itemCount' => $this->itemCount(), 'providers' => $providers];
    }
}
