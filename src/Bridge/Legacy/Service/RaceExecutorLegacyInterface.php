<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface RaceExecutorLegacyInterface
{
    public function race(array $candidate, int $timeoutMs): array;
}
