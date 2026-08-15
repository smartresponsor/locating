<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface SlaPolicyInterface
{
    public function weight(float $p95MsTarget, float $p95MsObserved, float $errorTarget, float $errorObserved): float;
}
