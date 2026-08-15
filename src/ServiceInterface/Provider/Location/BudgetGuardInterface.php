<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

interface BudgetGuardInterface
{
    public function setCap(string $tenantId, string $op, float $cap): void;

    /** @return array{0:float,1:float} */
    public function stat(string $tenantId, string $op): array;

    public function canSpend(string $tenantId, string $op, float $cost): bool;

    public function charge(string $tenantId, string $op, float $cost): bool;
}
