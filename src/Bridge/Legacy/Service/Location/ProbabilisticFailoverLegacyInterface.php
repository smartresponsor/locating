<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface ProbabilisticFailoverLegacyInterface
{
    public function should(float $health, float $errorRate): bool;
}
