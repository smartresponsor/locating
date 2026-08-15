<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Experiment;

interface CanaryGuardInterface
{
    public function decide(float $errorRate, float $p95Ms, float $budgetBurn): string;
}
