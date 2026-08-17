<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface AdaptiveOrderingInterface
{
    /**
     * @param list<string> $provider
     * @param array<string, array{latency_ms?:float|int, error_rate?:float|int, health?:float|int}> $signal
     * @param array<string, float|int> $cost
     * @param array<string, mixed> $hint
     * @return list<string>
     */
    public function order(string $region, array $provider, array $signal, array $cost, array $hint): array;
}
