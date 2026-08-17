<?php

declare(strict_types=1);

namespace App\Locating\Service\Provider\Location\Runtime\Routing;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Routing\StrategyRegistryInterface;

final class StrategyRegistry implements StrategyRegistryInterface
{
    /** @var array<string,object> */
    private array $providers = [];

    public function add(object $p): void
    {
        $name = $p::class;
        if (method_exists($p, 'nameEntity')) {
            $candidate = $p->nameEntity();
            if (is_string($candidate) && '' !== $candidate) {
                $name = $candidate;
            }
        }
        $this->providers[$name] = $p;
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
