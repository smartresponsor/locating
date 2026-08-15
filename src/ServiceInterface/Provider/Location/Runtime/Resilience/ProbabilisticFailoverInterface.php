<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface ProbabilisticFailoverInterface
{
    public function should(float $health, float $errorRate): bool;
}
