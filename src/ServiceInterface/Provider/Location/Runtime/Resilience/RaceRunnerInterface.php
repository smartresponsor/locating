<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface RaceRunnerInterface
{
    /**
     * @param array<string,callable():mixed> $candidate
     * @return array{id:string,value:mixed}
     */
    public function run(array $candidate, int $softTimeoutMs = 800): array;
}
