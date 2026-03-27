<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface RaceRunnerLegacyInterface
{
    public function run(array $candidate, int $softTimeoutMs = 800): array;
}
