<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface SlaPolicyLegacyInterface
{
    public function weight(float $p95MsTarget, float $p95MsObserved, float $errorTarget, float $errorObserved): float;
}
