<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface StrategyRegistryInterface
{
    public function add(object $p): void;

    public function byName(string $nameEntity): ?object;

    /** @return list<string> */
    public function names(): array;
}
