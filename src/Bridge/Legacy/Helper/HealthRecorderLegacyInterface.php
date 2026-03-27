<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Helper\Location;

interface HealthRecorderLegacyInterface
{
    public function ok(string $key, float $latencyMs): void;

    public function fail(string $key, float $latencyMs): void;

    /** @return array{ok:int,fail:int,avg_ms:float,error_rate:float} */
    public function snapshot(string $key): array;
}
