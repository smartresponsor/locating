<?php

declare(strict_types=1);

namespace Smartresponsor\Integration\Locator\Registry;

use App\Bridge\Legacy\Contract\Location\LocatorProviderLegacyInterface;

final class LocatorPluginRegistry
{
    /** @var list<LocatorProviderLegacyInterface> */
    private array $providers = [];

    public function register(LocatorProviderLegacyInterface $p): void
    {
        $this->providers[] = $p;
    }

    /** @return list<LocatorProviderLegacyInterface> */
    public function all(): array
    {
        return $this->providers;
    }

    public function healthiest(): ?LocatorProviderLegacyInterface
    {
        $candidates = array_values(array_filter($this->providers, fn ($p) => $p->isHealthy()));
        if ([] === $candidates) {
            return null;
        }
        usort($candidates, fn ($a, $b) => $b->getPriority() <=> $a->getPriority());

        return $candidates[0];
    }
}
