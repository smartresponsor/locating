<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderBudgetLegacyInterface
{
    public function allow(string $name): bool;
}
