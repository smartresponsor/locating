<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Registry;

use App\Locating\Contract\Location\LocatorProviderContract;

final class LocatorPluginRegistry
{
    /** @var list<LocatorProviderContract> */
    private array $providers = [];

    public function register(LocatorProviderContract $p): void
    {
        $this->providers[] = $p;
    }

    /** @return list<LocatorProviderContract> */
    public function all(): array
    {
        return $this->providers;
    }

    public function healthiest(): ?LocatorProviderContract
    {
        $candidates = array_values(array_filter($this->providers, fn ($p) => $p->isHealthy()));
        if ([] === $candidates) {
            return null;
        }
        usort($candidates, fn ($a, $b) => $b->getPriority() <=> $a->getPriority());

        return $candidates[0];
    }
}
