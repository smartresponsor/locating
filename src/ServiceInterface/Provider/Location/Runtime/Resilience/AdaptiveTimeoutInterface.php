<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp.
 */
interface AdaptiveTimeoutInterface
{
    public function observe(float $latencyMs): void;

    public function timeout(): int;
}
