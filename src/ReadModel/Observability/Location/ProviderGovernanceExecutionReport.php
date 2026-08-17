<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExecutionItemInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExecutionReportInterface;

final class ProviderGovernanceExecutionReport implements ProviderGovernanceExecutionReportInterface
{
    /** @param array<string, ProviderGovernanceExecutionItemInterface> $providers */
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

    /** @return array<string, ProviderGovernanceExecutionItemInterface> */
    public function providers(): array
    {
        return $this->providers;
    }

    /** @return array{service:string,itemCount:int,providers:array<string,array{sourceKey:string,decision:string,severity:string,acknowledgementState:string,steps:list<array{code:string,priority:string,ownerHint:string,status:string,acknowledgementRequired:bool}>}>} */
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
