<?php

declare(strict_types=1);

namespace App\Locating\Service\Provider\Location\Runtime\Routing;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Routing\StrategyRegistryInterface;

final class StrategyRegistry implements StrategyRegistryInterface
{
    private array $providers = [];

    public function add(object $p): void
    {
        $this->providers[method_exists($p, 'nameEntity') ? $p->nameEntity() : $p::class] = $p;
    }

    public function byName(string $nameEntity): ?object
    {
        return $this->providers[$nameEntity] ?? null;
    }

    /** @return array<int,string> */
    public function names(): array
    {
        return array_keys($this->providers);
    }
}
