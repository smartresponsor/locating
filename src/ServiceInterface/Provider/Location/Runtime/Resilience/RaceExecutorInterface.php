<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface RaceExecutorInterface
{
    public function race(array $candidate, int $timeoutMs): array;
}
