<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\ProviderGovernanceExecutionReportInterface;

final class ProviderGovernanceExecutionReport implements ProviderGovernanceExecutionReportInterface
{
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
