<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\CostCapPolicyInterface;

final class CostCapPolicyService implements CostCapPolicyInterface
{
    public function allow(float $unitCost, float $cap): bool
    {
        return $unitCost <= max(0.0, $cap);
    }
}
