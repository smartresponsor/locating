<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface RaceExecutorInterface
{
    /**
     * @param array<string,float|int> $candidate
     * @return array{status:'ok',provider:string,latency_ms:float|int}|array{status:'error',error:string}
     */
    public function race(array $candidate, int $timeoutMs): array;
}
