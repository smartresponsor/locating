<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface CanaryGuardLegacyInterface
{
    public function decide(float $errorRate, float $p95Ms, float $budgetBurn): string;
}
