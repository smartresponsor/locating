<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceReportInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceSnapshotInterface;

final class ProviderGovernanceReport implements ProviderGovernanceReportInterface
{
    /**
     * @param array<string, ProviderGovernanceSnapshotInterface> $providers
     */
    public function __construct(
        private readonly string $service,
        private readonly array $providers,
    ) {
    }

    public function service(): string
    {
        return $this->service;
    }

    public function providerCount(): int
    {
        return count($this->providers);
    }

    public function degradedCount(): int
    {
        $count = 0;

        foreach ($this->providers as $provider) {
            if ($provider->successRate() < 0.9 || false === $provider->quotaAllowed()) {
                ++$count;
            }
        }

        return $count;
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
            'providerCount' => $this->providerCount(),
            'degradedCount' => $this->degradedCount(),
            'providers' => $providers,
        ];
    }
}
