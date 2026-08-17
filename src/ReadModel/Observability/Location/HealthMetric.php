<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ReadModel\Observability\Location;

final class HealthMetric
{
    /** @return array{provider:string,region:string,health:float,ts:int} */
    public function serialize(string $provider, string $region, float $health, int $ts): array
    {
        return ['provider' => $provider,'region' => $region,'health' => $health,'ts' => $ts];
    }
}
