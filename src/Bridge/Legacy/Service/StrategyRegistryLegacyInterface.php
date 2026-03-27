<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface StrategyRegistryLegacyInterface
{
    public function add(object $p): void;

    public function byName(string $name): ?object;

    public function names(): array;
}
