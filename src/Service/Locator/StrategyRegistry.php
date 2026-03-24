<?php

declare(strict_types=1);

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\StrategyRegistryLegacyInterface;

final class StrategyRegistry implements StrategyRegistryLegacyInterface
{
    private array $providers = [];

    public function add(object $p): void
    {
        $this->providers[method_exists($p, 'name') ? $p->name() : $p::class] = $p;
    }

    public function byName(string $name): ?object
    {
        return $this->providers[$name] ?? null;
    }

    /** @return array<int,string> */
    public function names(): array
    {
        return array_keys($this->providers);
    }
}
