<?php

declare(strict_types=1);

namespace App\Locating\ReadModelInterface\Observability\Location;

interface HealthRecorderInterface
{
    public function ok(string $key, float $latencyMs): void;

    public function fail(string $key, float $latencyMs): void;

    /** @return array{ok:int,fail:int,avg_ms:float,error_rate:float} */
    public function snapshot(string $key): array;
}
